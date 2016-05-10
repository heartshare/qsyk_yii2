{set title=$resource->desc}
{registerCssFile url='@web/css/style.css'}

<div class="body-content">
    <div class="row">
        <div class="col-xs-6 ui-item-header">
            <div class="avatar-img">
                {* 示例： http://ic.appcq.cn//img/show/sid/O4EcMzacGYw/h/120/w/120/show.jpg*}
                <img src="http://ic.appcq.cn/img/show/sid/{$resource->userAvatar}/h/120/w/120/t/1/show.jpg"
                     class="img-circle">
            </div>
            <div class="brief-info">
                <p class="no-spacing medium-size">{$resource->userName}</p>
                <small>{$resource->pubTimeElapsed}</small>
            </div>
        </div>

        <div class="col-xs-6">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <p class="big">
                {$resource->desc}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 ui-img ui-grid-bottom">
            <img src="http://ic.appcq.cn//img/show/sid/u_VvSg0Mwg0/h/1000/w/654/t/0/show.jpg" class="img-thumbnail">
        </div>
    </div>
    <div class="row ui-grid-bottom">
        <div class="col-xs-3">
            <p class="text-center light-color"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 200</p>
        </div>
        <div class="col-xs-3">
            <p class="text-center light-color"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> 200</p>
        </div>
        <div class="col-xs-3">
            <p class="text-center light-color"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> 0</p>
        </div>
        <div class="col-xs-3">
            <button type="button" class="btn btn-danger btn-sm">下一条</button>

        </div>
    </div>
    <div class="row">

        <div class="col-xs-12">
            <blockquote>
                <p>推荐</p>
            </blockquote>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 ui-img">
            <img src="http://ic.appcq.cn//img/show/sid/u_VvSg0Mwg0/h/1000/w/654/t/0/show.jpg" class="img-thumbnail">
            <p>描述1</p>
        </div>
        <div class="col-xs-6 ui-img">
            <img src="http://ic.appcq.cn//img/show/sid/u_VvSg0Mwg0/h/1000/w/654/t/0/show.jpg" class="img-thumbnail">
            <p>描述2</p>
        </div>

        <div class="col-xs-6 ui-img">
            <img src="http://ic.appcq.cn//img/show/sid/u_VvSg0Mwg0/h/1000/w/654/t/0/show.jpg" class="img-thumbnail">
            <p>描述3</p>
        </div>
        <div class="col-xs-6 ui-img">
            <img src="http://ic.appcq.cn//img/show/sid/u_VvSg0Mwg0/h/1000/w/654/t/0/show.jpg" class="img-thumbnail">
            <p>描述4</p>
        </div>
    </div>
</div>