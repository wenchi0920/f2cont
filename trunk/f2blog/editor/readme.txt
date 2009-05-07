编辑器自制说明：

１、在editor目录下建立一个editor_××.xml配置文件（其中××为你的编辑器简称），用于后台可以自动加载你新增的编辑器，
　　内容为你的编辑器名称与路径。
	具体格式如下：
          <?xml version="1.0" encoding="utf-8" ?>
          <editor>
	        <EditorName>tiny</EditorName>
	        <EditorDecription>TinyEditor</EditorDecription>
	        <EditorPath>editor/editor.php</EditorPath>
	        <EditorCode>tiny</EditorCode>
          </editor>

２、在你要安装的编辑器目录下建立一个editor.php，要与xml文件中的EditorPath的路径相对应。
　　此用于加载编辑器，具体代码如下：其中ＰＯＳＴ的名称必须是logContent。
   <tr>
     <td colspan="4">
	<textarea name="logContent" id="logContent" cols="100" rows="15" style="overflow:auto;font-size:10pt;width:99%;"  onkeydown="quickpost(event)" onfocus="getActiveText(this)" onclick="getActiveText(this)"  onchange="getActiveText(this)" tabindex="2"><?=$logContent?></textarea>
     </td>
   </tr>

３、目前官方已提供了tiny,fckeditor,ewebeditor,ubb四种编辑器的接口，如果相应的可以到官方论坛取得相关的资料。