(function ($) {
   // 联系我们
   var baseUrl = "/admin";
   window.contact = function () {
    $.ajax({
      url: baseUrl + "/api/content/contact",
      // 暂时只支持图片上传
      // 我测试没有测试上传文件 你看前端怎么处理的
      data: {name: "xxx", email: "xxx@mail.com", message: "message", file: "file object"},
      type: "post",
      success: function (res) {
        console.log(res);
        // 失败 成功状态在 res["status"] 下面
        // 示例:
        //Object {status: 0, message: "success", data: Array[0]}
        // 失败 提示稍后重试
      }
    });
   };
   
   window.newsletter = function () {
    $.ajax({
      url: baseUrl + "/api/content/newsletter",
      data: {name: "xxx", email: "xxx@mail.com", phone: "15821121753"},
      type: "post",
      success: function (res) {
        console.log(res);
        // 失败 成功状态在 res["status"] 下面
        // 示例:
        //Object {status: 0, message: "success", data: Array[0]}
        // 失败 提示稍后重试
      }
    });
   };
   
   window.wantobuy = function () {
    $.ajax({
      url: baseUrl + "/api/content/wantobuy",
      // 产品ID 在 HTML 属性里面获取，API 会检测产品是否有效
      data: {name: "xxx", email: "xxx@mail.com", phone: "15821121753", product: "20391"},
      type: "post",
      success: function (res) {
        console.log(res);
        // 失败 成功状态在 res["status"] 下面
        // 示例:
        //Object {status: 0, message: "success", data: Array[0]}
        // 失败 提示稍后重试
      }
    });
   };

})(jQuery);