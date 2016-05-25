[推送相关](/JPUSH.md)

API
===


**\[REST\]**表示接口为RESTful风格接口
**\[Auth\]**表示Header需要传Authorization

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
       type=[0,1,2,3]&dynamic=[0,1]
       
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
         
              
