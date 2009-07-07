<?php
/*Khalid XML files parser :: class kxparse, Started in March 2002 by Khalid Al-kary*/
class kxparse{
var $xml;
var $cursor;
var $cursor2;

//the constructor $xmlfile is the file you want to load into the parser
function kxparse($xmlfile)
{
	//just read the file 
	$file=fopen($xmlfile,"r");
	
	//put the text inside the file in the XML object variable
	while (!feof($file))
		{
			$this->xml.=fread($file,4096);		
		}
	
	//close the opened file 
	fclose($file);

	//set the cursor to 0 (start of document), the cursor is later used by another functions
	$this->cursor=0;

	//set the second curosr to the end of document
	$this->cursor2=strlen($this->xml);
}

/*this function first gets a copy of the XML file starting from cursor and ending with cursor2
and then counts the number of occurences of the given tag name inside that area
returns an array (occurrence index -> occurence position in the XML file)
this function is half of the engine that moves Kxparse */
function track_tag_cursors($tname)
	{
		//getting the copy as intended
		$currxml=substr($this->xml,$this->cursor,$this->cursor2);
		
		//counting the number of occurences in the cut area
		$occurs=substr_count($currxml,"<".$tname);
		
		//the aray that will be returned
		$tag_poses=array();
		
		//setting its 0 to 0 because indeces in Kxparse start from 1
		$tag_poses[0]=0;
		
		//for each of the occurences
		for ($i=1;$i<=$occurs;$i++)
			{
				
				if ($i!=1)
					{
						//if it's not the first occurence 
						//start checking for the next occurence but first cut the previous occurences off from the string
						$tag_poses[$i]=strpos($currxml,"<".$tname,$tag_poses[$i-1]+1-$this->cursor)+$this->cursor;
					}
				else
					{
						//if its the first occurence just assign its value + the cursor (because the position is in the XML file wholly
						$tag_poses[$i]=strpos($currxml,"<".$tname)+$this->cursor;
					}
					
			}
		
		//return that array	
		return $tag_poses;
	}
//this function strips and decodes the tag text...
function get_tag_text_internal($tname)
	{
		//strip the tags from the returned text and the decode it
		return $this->htmldecode(strip_tags($tname));
	}

//function that returns a particular attribute value ... 
//tag is the tag itself(with its start and end)
function get_attribute_internal($tag,$attr)
	{
		//identifying the character directly after the tag name to cut it then
		if (strpos($tag," ")<strpos($tag,">"))
			{
				$separ=" ";
			}
		else
			{
				$separ=">";
			}

		//cutting of the tag name according to separ
		$tname=substr($tag,1,strpos($tag,$separ)-1);

		//cut the tag starting from the white space after the tag name, ending with(not containing) the > of the tag start
		$work=substr($tag,strlen($tname)+1,strpos($tag,">")-strlen($tname)-1);

		//get the index of the tag occurence inside $work
		$index_of_attr=strpos($work," ".$attr."=\"")+1;

		//check if the attribute was found in the tag
		if ($index_of_attr)
			{
				//now get the attributename+"=""+attrbutevalue+""" and extract the value from between them
				//calculate from where we will cut
				$index_of_value=$index_of_attr+strlen($attr)+2;

				//cut note the last argument for calculating the end
				$work=substr($work,$index_of_value,strpos($work,"\"",$index_of_value)-$index_of_value);

				//now return the attribute value
				return $work;
			}

			//if the attribute wasn't found, return false'
		else
			{
				return FALSE;
			}
	}


//this function HTML-decodes the var $text...
function htmldecode($text)
	{
		$text=str_replace("&lt;","<",$text);
		$text=str_replace("&gt;",">",$text);
		$text=str_replace("&amp;","&",$text);
		$text=str_replace("&ltt;","&lt;",$text);
		$text=str_replace("&gtt;","&gt;",$text);
		return $text;
	}

//the function that saves a file to a particular location
function save($file)
	{
		//open the file and overwrite of already avilable
		$my_file=fopen($file,"wb");

		//$my_status holds wether the operation is okay
		$my_status=fwrite($my_file,$this->xml);

		//close the file handle
		fclose($my_file);

		if ($my_status!=-1)
			{
				return TRUE;
			}
		else
			{
				return FALSE;
			}

	}

//function that gets a tag in the XML tree (with its starting and ending)
function get_tag_in_tree($tname,$tindex)
	{
		$this->get_work_space($tname,$tindex);
		return substr($this->xml,$this->cursor,$this->cursor2-$this->cursor);
	}
//function that gets the text of a tag
function get_tag_text($tname,$tindex)
{
	$mytag=$this->get_tag_in_tree($tname,$tindex);
	return $this->get_tag_text_internal($mytag);
}	
//funtion that counts the number of occurences of a tag in the XML tree	
function count_tag($tname,$tindex)
	{
		return $this->get_work_space($tname,$tindex);
	}
	
//functoin that gets the attribute value in a tag	
function get_attribute($tname,$tindex,$attrname)	
	{
		$mytag=$this->get_tag_in_tree($tname,$tindex);
		return $this->get_attribute_internal($mytag,$attrname);
	}

//Very important function, half of the engine
//sets the $this->cursor and $this->cursor2 to the place where it's intended to work	
function get_work_space($tname,$tindex)	
	{
		//counts the number of ":"  in the given colonedtagindex
		$num_of_search=substr_count($tindex,":");
		
		//counts the number of ":" in the given colonedtagname
		$num_of_search_text=substr_count($tname,":");
		
		//checks if they are not equal this regarded an error
		if ($num_of_search!=$num_of_search_text)
			{
				return false;
			}
		else
			{
				//now get the numbers in an array
				$search_array=explode(":",$tindex);
				
				//and also get the corresponding tag names
				$search_text_array=explode(":",$tname);
				
				//set the cursor to 0 in order to erase former work
				$this->cursor=0;
				
				//set the cursor2 to the end of the file for the same reason
				$this->cursor2=strlen($this->xml);
				
				//get the first tag name to intiate the loop
				$currtname=$search_text_array[0];
				
				//get the first tag index to intiate the loop
				$currtindex=$search_array[0];
				
				//the loop according to number of ":"
				for ($i=0;$i<count($search_array);$i++)
					{
						//if it's not the first tag name and index
						if ($i!=0)
							{
								//so append the latest colonedtagname to the current tag name
								$currtname=$currtname.":".$search_text_array[$i];
								
								//and append the latset colonedtagindex to the current tag index
								$currtindex=$currtindex.":".$search_array[$i];
							}
						//$arr holds the number of occurences of the current tag name between the cursor and cursor2	
						$arr=$this->track_tag_cursors($search_text_array[$i]);
						
						//the index which you want to get the position of 
						$tem=$search_array[$i];
						
						//to support count_tag_in_tree
						//when given a ? it returns the number of occurences of the current tag name
						if ($tem=="?")
							{
								return count($arr)-1;
							}
						else {	
						
						//to support the auto-last method
						//if the current tag index equals "-1" so replace it by the last occurence index
						if ($tem==-1)	
							{
								$tem=count($arr)-1;
							}
						
						//now just set cursor one to the occurence position in the XML file accrding to $tem	
						$this->cursor=$arr[(int)$tem];
						
						//and set cursor2 at the end of that tag
						$this->cursor2=strpos($this->xml,"</".$search_text_array[$i].">",$this->cursor)+strlen("</".$search_text_array[$i].">");
							}
					}
			}	
}
//the function that appends a tag to the XML tree
function create_tag($tname,$tindex,$ntname)	
	{
		//first get the intended father tag
		$this->get_work_space($tname,$tindex);
		
		//explode the given colonedtagname into an array
		$search_text_array=explode(":",$tname);
		
		//after setting the cursors using get_work_space
		//get a cope of the returned tag
		$workarea=substr($this->xml,$this->cursor,$this->cursor2-$this->cursor);
		
		//calculate the place where you will put the tag start and end
		$inde=$this->cursor+strpos($workarea,"</".$search_text_array[count($search_text_array)-1].">");
		
		//here, replace means insert because the length argument is set to 0
		$this->xml=substr_replace($this->xml,"<".$ntname."></".$ntname.">",$inde,0);
	}
//the function that sets the value of an attribute	
function set_attribute($tname,$tindex,$attr,$value)
	{
		//first set the cursors using get_work_space
		$this->get_work_space($tname,$tindex);
		
		//now get a copy of the XML tag between cursor and cursor2
		$currxml=substr($this->xml,$this->cursor,$this->cursor2-$this->cursor);
		
		//cut the area of the tag on which you want to work
		//starting from the tag "<" and ending with the opening tag ">"
		$work=substr($currxml,0,strpos($currxml,">")+1);
		
		//if the attribute is already available
		if (strpos($work," ".$attr."=\""))
		{
			//calculate the current value's length
			$currval_length=strlen($this->get_attribute_internal($currxml,$attr));
			
			//get the position of the attribute inside the tag
			$my_attribute_pos=strpos($work," ".$attr."=\"")+1;
			
			//get the length of the attribute
			$my_attribute_length=strlen($attr);
			
			//now replace the old value
			$this->xml=substr_replace($this->xml,$value,$this->cursor+$my_attribute_pos+$my_attribute_length+2,$currval_length);
			return TRUE;
		}
		
		//if the attribute wasn't already available'
		else
		{
			//check if there are other attributes in the tag
			if (strpos($work," "))
				{
					$separ=" ";
				}
			else
				{
					$separ=">";
				}
			
			//prepare the attribute 
			$newattr=" ".$attr."=\"".$value."\"";
			
			//insert the new attribute
			$this->xml=substr_replace($this->xml,$newattr,$this->cursor+strpos($work,$separ),0);
			return TRUE;
		}	
}
//the function that changes or adds the text of a tag
function set_tag_text($tname,$tindex,$text)
	{
		//firs get set the cursors using get_work_space
		$this->get_work_space($tname,$tindex);
		
		//explode the given colonedtagname in an array
		$search_text_array=explode(":",$tname);
		
		//get the latest name
		$currtname=$search_text_array[count($search_text_array)-1];
		
		//calculate the start of replacement
		$replace_start_index=strpos($this->xml,">",$this->cursor)+1;
		
		//calculate the end of replacement
		$replace_end_index=strpos($this->xml,"</".$currtname.">",$this->cursor)-1;
		
		//calculate the length between them
		$tem=$replace_end_index-$replace_start_index+1;
		
		//and now replace 
		$this->xml=substr_replace($this->xml,$text,$replace_start_index,$tem);
	}
//functio that removes a tag	
function remove_tag($tname,$tindex)	
	{
		//set the cursors using get_work_space
		$this->get_work_space($tname,$tindex);
		
		//now replace with ""
		$this->xml=substr_replace($this->xml,"",$this->cursor,$this->cursor2-$this->cursor);
	}

}
?>