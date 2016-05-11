API
===
\[Auth\]表示Header需要传Authorization
示例：
        Authorization:Bearer [token]
### 用户注册\[POST\]
      /user/register       
参数
    -   uuid : 设备uuid
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
    -   sid : 资源sid
    
### 资源顶\[POST\]\[Auth\]
       /resource/like     
参数
    -   sid : 资源sid
        
### 资源踩\[POST\]\[Auth\]
       /resource/hate       
参数
    -   sid : 资源sid
    
### 资源举报\[POST\]\[Auth\]
       /resource/hate       
参数
    -   sid : 资源sid
    -   type : 举报类型

### 资源收藏列表\[GET\]\[Auth\]
     /favorite       
       
### 赞过的资源列表\[GET\]\[Auth\]
      /like      
