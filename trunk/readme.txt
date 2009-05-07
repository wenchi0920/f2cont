第一版整合各类已知漏洞修补，Bug修正，以及一些功能的改进和优化。
整体和F2blog Ver 1.2 Build 0301没有大的区别，数据表结构没有改变。

更新Notes:
漏洞修正
view plaincopy to clipboardprint?
xmlrpc上传任意文件漏洞修正  
暴路径补丁修正（小瓜）  
后台绕过权限修改默认模板漏洞修正（小瓜）  
验证码增强补丁（Joesen）  
评论和留言内容过滤器无效问题修正（Phileas）  
修正列表模式暴露username的Bug（Phileas）  
功能改进
view plaincopy to clipboardprint?
简化验证码显示以适应背景（Phileas）  
前台文章管理功能删除及清空时加入确认提示以避免误操作  
修改open_img方程在新窗口正确显示大图片，且IE下可滚轮缩放  
采用新一套GundamSeed人物头像更好适应深色背景  
增加评论框内背景图片（可在皮肤css选则是否显示）  
改进一些英文lang语言档翻译  
升级FCK editor至新版2.6.3  
修正firefox3下标签插入有问题的小bug  
模板
view plaincopy to clipboardprint?
新款默认皮肤F2cont设计，兼容ie7&firefox3  
安装部分
view plaincopy to clipboardprint?
修改安装时推荐文件夹属性为755（增强安全性）  
调整优化一些默认设置（自动保存，默认生成缓存，开启ajax，以及其他一些细节）  
安装时默认加入必要的内容过滤以抵御spam  

使用方法：
安装
view plaincopy to clipboardprint?
FTP上传全部文件至网站服务器  
运行http://your_url/install/install.php  
依照安装提示操作各步骤  
安装完成删除install文件夹。完毕。  
从F2blog V1.2升级
view plaincopy to clipboardprint?
删除自己站内editor/fckeditor文件夹所有内容  
删除安装包内include/config.php  
上传安装包内全部文件并覆盖。完成。  