ThinkPHP5.1 + layui开发，默认集成了权限管理、模块管理、插件管理、钩子管理、数据库管理等常用功能，以方便开发者快速构建自己的应用。

## 目录结构
```
www  WEB部署目录（不建议使用子目录）
├─application          应用目录
│  ├─system            系统基础模块（禁止修改）
│  ├─common            公共模块目录
│  │   ├─behavior      行为目录
│  │   ├─controller    公共模块控制器目录
│  │   ├─model         公共模型目录
│  │   ├─validate      公共验证器目录
│  │   ├─taglib        标签库目录
│  │   │   ├─Hisi.php  Hisi通用标签库
│  │   │   └─ ...      更多自定义标签库
│  │   └─ ...          更多类库目录
│  │
│  ├─index             前台默认模块
│  │  ├─home           前台控制器目录
│  ├─install           系统安装模块（安装成功后可删除）
│  ├─module_name       模块目录（请使用开发助手创建）
│  │  ├─admin          后台控制器目录
│  │  ├─home           前台控制器目录
│  │  ├─model          模型目录
│  │  ├─view           后台视图目录
│  │  ├─config         配置目录
│  │  ├─common.php     模块函数文件
│  │  └─ ...           更多类库目录
│  │
│  ├─command.php        命令行定义文件
│  ├─common.php         公共函数文件（禁止修改）
│  ├─function.php       **为方便系统升级，二次开发中用到的公共函数请写在此文件**
│  ├─install.lock       安装成功之后自动生成（禁止删除）
│  └─tags.php           应用行为扩展定义文件
│
├─backup                备份目录
│
├─config                应用配置目录
│  ├─module_name        模块配置目录
│  │  ├─database.php    数据库配置
│  │  ├─cache           缓存配置
│  │  └─ ...            
│  │
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─hs_cloud.php       云平台配置（禁止修改）
│  ├─hs_system.php      基础配置（禁止修改）
│  ├─log.php            日志配置
│  ├─session.php        Session配置
│  ├─template.php       模板引擎配置
│  └─trace.php          Trace配置
│
├─route                 路由定义目录
│  ├─hisi.php           基础路由（禁止修改）
│  ├─route.php          路由定义
│  └─ ...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─static             静态资源目录
│  │   ├─fonts          字体图标目录
│  │   ├─js             js资源目录
│  │   │   ├─editor     网页编辑器目录
│  │   │   ├─fileupload 文件上传
│  │   │   ├─layer      layer弹窗
│  │   │   ├─layui      layui
│  │   │   ├─jquery.2.1.4.min.js 	Jquery
│  │   │   ├─jquery.qrcode.min.js 	Jquery生成二维码插件
│  │   │   └─query.SuperSlide.2.1.1.js 	Jquery幻灯片插件
│  │   ├─plugins        插件静态资源目录
│  │   ├─system         后台静态资源目录
│  │   ├─module_name    扩展模块资源目录
│  │   └─ ......         更多
│  │
│  ├─theme              前台模板目录
│  │   ├─module_name    扩展模块资源目录
│  │   └─ ......         更多
│  │
│  ├─upload             资源上传目录
│  ├─index.php          默认入口文件
│  ├─admin.php          后台入口文件
│  ├─robots.txt         Robots协议
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  └─logo.png           框架LOGO文件
│
├─extend                扩展类库目录
│  ├─hisi               基础类库（禁止修改）
│  │  ├─Cloud.php       云平台类
│  │  ├─Database.php    数据库操作类
│  │  ├─Dir.php         文件或文件夹操作类
│  │  ├─Download.php    文件下载类
│  │  ├─Http.php        Http请求类
│  │  ├─PclZip.php      压缩包操作类
│  │  └─Xml.php         xml操作类
│  │
│  └─ ......            更多
│
├─plugins               插件目录
│  ├─hisiphp            系统基础信息插件
│  ├─plugins_name       扩展插件目录
│  └─ ......            更多
│
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─.env                  环境变量配置
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
├─version.php           框架版本信息
```


## 鸣谢
感谢[ThinkPHP](http://www.thinkphp.cn)、[JQuery](http://jquery.com)、[Layui](http://www.layui.com)等优秀开源项目。

