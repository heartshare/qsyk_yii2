[推送相关](/JPUSH.md)

API
===

注：
**\[REST\]**表示接口为RESTful风格接口
**\[Auth\]**表示Header需要传Authorization
参数\*表示参数可选

示例：

        Authorization:Bearer [token]
### 用户注册\[POST\]
      /user/register       
参数
- uuid : 设备uuid

返回

        {
          "status": 0,
          "message": "",
          "user": {
            "auth_key": "token"
          }
        }
### 资源收藏\[POST\]\[Auth\]

       /resource/fav       
参数
- sid : 资源sid
    
### 资源顶\[POST\]\[Auth\]

       /resource/like     
参数
- sid : 资源sid
        
### 资源踩\[POST\]\[Auth\]

       /resource/hate       
参数
- sid : 资源sid
    
### 资源举报\[POST\]\[Auth\]

       /resource/report       
参数
- sid : 资源sid
- type : 举报类型

### 资源收藏列表\[GET\]\[Auth\]

     /favorite       
       
### 赞过的资源列表\[GET\]\[Auth\]

      /like      


### 分享任务\[POST\]\[Auth\]

       /user/share-task  
            
### 登录任务\[POST\]\[Auth\]

       /user/sign-task    
       
### 用户信息\[GET\]\[Auth\]

       /user/info  
       
可选参数：
       expand=taskList
       
### 用户任务列表\[GET\]\[Auth\]

       /user-tasks  
       
### 资源列表\[GET\]\[REST\]
       
       /resources
       
参数：
       type=[0-5];0全部1文字2图片3视频4语音5动图
         
       
扩展:
       expand=godPosts[神评论],hotPosts[热门评论],posts[评论]
       
### 资源标签列表\[GET\]\[REST\]
       
       /resource-tags
       
参数：
       tag=[sid]
       
### 评论顶\[POST\]\[Auth\]

       /post/like     
参数
- sid : 资源sid

### 手机号注册\[POST\]\[Auth\]

       /v2/user/register   
参数
- mobile : 手机号
- password : 密码
- client : client id
- client_secret : client secret
- nickname : 昵称，中英文数字下划线，长度2-12
- avatarFile\* : 上传头像文件

### 第三方注册\[POST\]\[Auth\]

       /v2/user/third-register   
参数
- from : 值为qq，weixin，weibo
- oid : 第三方唯一标示
- client : client id
- client_secret : client secret
- nickname : 昵称，第三方请求的昵称
- avatarFile\* : 上传头像文件
- avatar\* : 上传头像url


### 手机号登录\[POST\]\[Auth\]

       /v2/user/login   
参数
- mobile : 手机号
- password : 密码
- client : client id
- client_secret : client secret


### 第三方登录\[POST\]\[Auth\]

       /v2/user/third-login   
参数
- from : 值为qq，weixin，weibo
- oid : 第三方唯一标示
- client : client id
- client_secret : client secret


### 请求短信验证码\[POST\]

       /v2/user/request-code   
参数
- mobile : 手机号

### 验证短信验证码\[POST\]

       /v2/user/verify-code   
参数
- mobile : 手机号
- code : 验证码

### 修改资料：密码，昵称，性别，头像\[POST\]\[Auth\]

       /v2/user/edit
参数
- nick_name\* : 昵称，中英文数字下划线，长度2-12，不允许重复，3个月只能改一次
- sex\* : 性别
- avatarFile\* : 头像文件
- password\* : 密码

说明：
    需要改哪项传对应字段，不需要修改的字段千万不要传！
    
### 手机号绑定\[POST\]\[Auth\]

       /v2/user/bind
参数
- mobile : 手机号
- password : 密码


### Token过期更新\[POST\]\[Auth\]

       /v2/user/refresh
参数
- refresh_token : 刷新token
- client : client id
- client_secret : client secret

### 发评论\[POST\]\[Auth\]

       /post/send
参数
- content : 评论内容
- sid : 资源sid
- reply\* : 回复评论的sid




         
              
