(function ($) {
  var AdminModule = angular.module("adminModule", ["ui.bootstrap"]);
  
  AdminModule.directive("ngCkeditor", function () {
    return {
      restrict: "A",
      require: ["ngModel"],
      scope: false,
      link: function (scope, element, attrs, ctrls) {
        var ngModel = ctrls[0];
        var ckeditor = CKEDITOR.replace(element[0], {customConfig: window.baseurl + "/scripts/config.js"});
        setInterval(function () {
          if(ckeditor.checkDirty()) {
            var data = ckeditor.getData();
            if (ngModel.$viewValue != data) {
              ngModel.$setViewValue(data);
            }
          }
        }, 200);
        
        ckeditor.on("instanceReady", function() {
          var data = ngModel.$viewValue;
          ckeditor.setData(data);
        });
      }
    };
  });
  
  // Ajax Scroll Up 动画
  AdminModule.directive("ngAjaxScrollUp", [function () {
    function startAnimate(scope, element, attrs) {
      scope.note = attrs["start"];
      element.find(".animate").animate({
        "margin-top": "-2.5em",
      }, 1000, function () {
        
      });
    }
    
    function finishAnimate(scope, element, attrs) {
      scope.note = attrs["finish"];
    }
    
    return {
      restrict: "E",
      scope: false,
      template: "<div class='animate'>{{note}}</animate>",
      link: function (scope, element, attrs) {
        $(document).ajaxComplete(function (){
          finishAnimate(scope, element, attrs);
        });
        
        $(document).ajaxStart(function () {
          startAnimate(scope, element, attrs);
        });
      }
    };
  }]);
  
  // 初始化Model
  AdminModule.directive("ngInitial", function () {
    return {
      restrict: "A",
      require: ["ngModel"],
      scope: false,
      link: function (scope, element, attrs, ctrl) {
            if (attrs.ngModel) {
                var value = attrs.value || element.val();
                if (element.is("select")) {
                  setTimeout(function () {
                    element.val(value);
                  });
                }
                ctrl[0].$setViewValue(value);
            }
        }
    };
  });
  
  // 弹出框
  AdminModule.factory("ui.Dialog", [function () {
      return {
        
      };
  }]);
  
  // 文件上传Service
  AdminModule.factory("UploadMediaService", ["$http" ,function ($http) {
    function upload(file, index) {
      if (typeof index == "undefined") {
        index = 0;
      }
      file = file.files[index];
      // 上传
      var formdata = new FormData();
      formdata.append("media", file);
      return $.ajax({
        url: window.baseurl + "/api/media/temp",
        type: "post",
        data: formdata,
        processData: false,
        contentType: false
      });
    };
    
    function uploadVideo(file) {
      file = file.files[0];
      // 上传
      var formdata = new FormData();
      formdata.append("media", file);
      return $.ajax({
        url: window.baseurl + "/api/media/videotemp",
        type: "post",
        data: formdata,
        processData: false,
        contentType: false
      });
    }
    
    return {
      upload: upload,
      uploadVideo: uploadVideo
    };
  }]);

  AdminModule.factory("ContentService", ["$http", function ($http) { 
        return {
          update: function (data) {
            return $.ajax({
              url: window.baseurl + "/api/content/update",
              data: $.param(data),
              type: "post"
            });
          }
        };
  }]);

  AdminModule.directive("ngUploadimage", ["UploadMediaService" ,function (UploadMediaService) {
    return {
      restrict: "E",
      scope: {},
      require: ["ngModel"],
      link: function (scope, element, attr, ctrl) {
        scope.src = [];
        scope.source = [];
        scope.multi = attr["multi"];
        if (typeof attr["multi"] != "undefined") {
          element.find("input[type='file']").change(function () {
            var maxFileSelected = angular.element(this)[0].files.length;
            for (var i = 0; i < maxFileSelected; i++) {
              UploadMediaService.upload(angular.element(this)[0], i).success(function (res) {
                if (typeof res["status"] && res["status"]  == 0) {
                  var uri = window.baseurl + res["data"]["uri"];
                  scope.src.push(uri);
                  scope.source.push(uri);
                  scope.$digest();
                  ctrl[0].$setViewValue(scope.src);
                }
              });
            }
          });
          if (typeof attr["value"] != "undefined") {
            var src = JSON.parse(attr["value"]);
            if (src) {
              ctrl[0].$setViewValue(src);
              scope.src = src;
            }
          }
          else {
            scope.src = [];
          }
        }
        else {
          element.find("input[type='file']").change(function () {
              $(this).after('<div class="uploading">Uploading...</div>');
            UploadMediaService.upload(angular.element(this)[0]).success(function (res) {
                $('.uploading').remove();
              if (typeof res["data"] == "undefined" || res["status"] != 0) {
                return;
              }
              ctrl[0].$setViewValue(res["data"]["uri"]);
              scope.src = [window.baseurl + res["data"]["uri"]];
              scope.$digest();
            });
          });

          ctrl[0].$setViewValue(attr["value"]);
          scope.src = [attr["value"]];
        }
        
        scope.removeItem = function (index) {
          scope.src.splice(index, 1);
          if (typeof multi == 'undefined') {
            ctrl[0].$setViewValue('');
          }
        };
      },
      template: function (el, attrs) {
        var name = attrs["ngModel"].replace(/^\w+\./, "");
        if (typeof attrs["multi"] == "undefined") {
          return '<div class="preview multi">' + 
            '<div class="multi-item" ng-repeat="s in src track by $index"><input type="hidden" name="'+name+'" value="{{s}}" /><span class="d-img" ng-click="removeItem($index)">Del</span><a target="_blank" ng-href="{{s}}"><img ng-src="{{s}}" alt="" /></a></div>' +
          '</div>' + 
          '<input type="file"  accept="image/*" upload="Upload Image"/>';
        }
        else {
          return '<div class="preview multi">' + 
            '<div class="multi-item" ng-repeat="s in src track by $index"><input type="hidden" name="'+name+'" value="{{s}}" /><span class="d-img" ng-click="removeItem($index)">Del</span><a target="_blank" ng-href="{{s}}"><img ng-src="{{s}}" alt="" /></a></div>' +
          '</div>' + 
          '<input type="file" multiple  accept="image/*" upload="Upload Image"/>';
        }
      },
    };
  }]);

  AdminModule.directive("ngUploadvideo", ["UploadMediaService", function (UploadMediaService) {
    return {
      restrict: "E",
      scope: {},
      require: ["ngModel"],
      link: function (scope, element, attr, ctrl) {
        element.find("input[type='file']").change(function () {
            $(this).next().html('Uploading...');
          UploadMediaService.uploadVideo(angular.element(this)[0]).success(function (res) {
            if (res["status"] != 0) {
              alert(res["message"]);
              return;
            }
            ctrl[0].$setViewValue(res["data"]["uri"]);
            scope.uri = res["data"]["uri"];
            scope.$digest();
          });
        });
        
        ctrl[0].$setViewValue(attr["value"]);
        scope.uri = attr["value"];
      },
      template: '<input type="file"  accept=".mp4" upload="Upload Video"/><span class="info">{{uri}}</span>'
    };
  }]);

  AdminModule.controller("ContentForm",  function ($scope, UploadMediaService, ContentService, $modal) {
    $scope.submitContent = function () {
      if ($scope.contentform.$valid) {
        ContentService.update($scope.content).success(function (res){
          if (res["status"]!= 0) {
            alert(res["message"]);
          }
          else {
            setTimeout(function () {
              window.location.href = angular.element("form[name='"+$scope.contentform["$name"]+"']").attr("redirect");
            }, 500);
          }
        });
      }
      else {
        // 把鼠标焦点定向到第一个有错误的元素上去
        var form = angular.element("form[name='"+$scope.contentform["$name"]+"']");
        var firstInvalid = form.find(".ng-invalid:first");
        firstInvalid.focus();
      }
    };
    
    var ModalInstanceCtrl = function ($scope, $modalInstance, cid, url) {
      $scope.cid = cid;
      
      $scope.ok = function () {
        $.ajax({
         url: window.baseurl + "/api/content/delete",
         data: {cid: cid},
         type: "POST"
       })
       .done(function () {
         $scope.message = "Delete success";
       })
       .always(function () {
         $modalInstance.dismiss('cancel');
         window.location.href = url;
       });
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
      
      if (cid) {
        $.ajax({
          url: window.baseurl + "/api/content",
          data: {cid: cid},
          type: "GET"
        }).done(function (res) {
          $scope.title = res["data"]["title"];
          $scope.body = res["data"]["body"];
          $scope.email = res["data"]["email"];
          $scope.$digest();
        });
      }
    };
    
    $scope.deleteConfirm = function (cid, url) {
      var modal = $modal.open({
        templateUrl: "deleteconfirm.html",
        controller: ModalInstanceCtrl,
        resolve: {
          cid: function () {
            return cid;
          },
          url: function () {
            return url;
          }
        }
      });
    };
  });


  AdminModule.controller("ContentTable", function ($scope, $modal) {
    
    var ModalInstanceCtrl = function ($scope, $modalInstance, cid) {
      $scope.cid = cid;
      
      $scope.ok = function () {
        $.ajax({
         url: window.baseurl + "/api/content/delete",
         data: {cid: cid},
         type: "POST"
       })
       .done(function () {
         $scope.message = "Delete success";
       })
       .always(function () {
         $modalInstance.dismiss('cancel');
         window.location.reload();
       });
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
      
      if (cid) {
        $.ajax({
          url: window.baseurl + "/api/content",
          data: {cid: cid},
          type: "GET"
        }).done(function (res) {
          $scope.title = res["data"]["title"];
          $scope.body = res["data"]["body"];
          $scope.email = res["data"]["email"];
          $scope.attachment = res['data']['file'];
          $scope.$digest();
        });
      }
    };
    
    var ModalViewInstanceCtrl = function ($scope, $modalInstance, cid, url) {
      $scope.cid = cid;
      $scope.src = url;
      
      $scope.ok = function () {
        $.ajax({
         url: window.baseurl + "/api/content/delete",
         data: {cid: cid},
         type: "POST"
       })
       .done(function () {
         $scope.message = "Delete success";
       })
       .always(function () {
         $modalInstance.dismiss('cancel');
         window.location.reload();
       });
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
      
      if (cid) {
        $.ajax({
          url: window.baseurl + "/api/content",
          data: {cid: cid},
          type: "GET"
        }).done(function (res) {
          $scope.title = res["data"]["title"];
          $scope.body = res["data"]["body"];
          $scope.email = res["data"]["email"];
          $scope.$digest();
        });
      }
    };
    
    
    $scope.deleteConfirm = function (cid) {
      var modal = $modal.open({
        templateUrl: "deleteconfirm.html",
        controller: ModalInstanceCtrl,
        resolve: {
          cid: function () {
            return cid;
          }
        }
      });
    };
    
    $scope.preview = function (cid) {
      var modal = $modal.open({
        template: $("#previewcontent").html(),
        controller: ModalInstanceCtrl,
        resolve: {
          cid: function () {
            return cid;
          }
        }
      });
    };
    
    $scope.viewimage = function (url) {
      $modal.open({
        template: $("#imagepreview").html(),
        controller: ModalViewInstanceCtrl,
        resolve: {
          cid: function () {
            return "";
          },
          url: function () {
            return url;
          }
        }
      });
    };
  });
  
  
  // 多语言切换
  angular.element("a[lang]").click(function () {
    jQuery.cookie('lang' , $(this).attr('lang') , { expires: 7, path: '/' });
    window.location.reload();
  });
  
  angular.element(document).ready(function () {
//    angular.element("textarea").ckeditor({
//      customConfig: window.baseurl + "/scripts/config.js"
//    });
    
    angular.element("#sidebar > .icons").click(function () {
      var icon = $(this);
      var sideBar = $(this).parent();
      if (icon.hasClass("fadeout")) {
        icon.removeClass("fadeout");
        sideBar.animate({
          left: 0,
          "margin-left": 0
        }, 1000, "easeInQuad", function () {
          icon.fadeIn("slow");
        });
      }
      else {
        var width = sideBar.width();
        sideBar.animate({
          left: -width,
          "margin-left": - ( parseInt(width) /2 )
        }, 1000, "easeInQuad", function () {
          icon.hide().addClass("fadeout").fadeIn("slow");
        });
      }
    });
    
    angular.element(".full-box").click(function () {
      var content = angular.element("#content");
      if (content.hasClass("full-screen")) {
        content.removeAttr('style').removeClass("full-screen");
      }
      else {
        content.animate({
          width: "99.1%",
          margin: "0px 0px"
        }, 1000, function () {
          content.addClass("full-screen");
        });
      }
    });
    
    var time;
    $(".thumbnail").live("mousemove", function () {
      var self = angular.element(this);
      if (!self.hasClass("checked")) {
        if (time) {
          clearTimeout(time);
        }
        time = setTimeout(function () {
        }, 200);
        
        angular.element(" > span", self).css("display", "block");
      }
    }).live("mouseleave" ,function (event) {
      var self = angular.element(this);
      if (!self.hasClass("checked")) {
        if (time) {
          clearTimeout(time);
        }
        time = setTimeout(function () {
        }, 200);
        
        angular.element(" > span", self).css("display", "none");
      }
    });

    $(".thumbnail > span .trash").live("click", function () {
      var thumbnail = $(this).parent().parent();
      if (thumbnail.hasClass("checked")) {
        $(this).removeAttr("style").parent().removeAttr("style");
        thumbnail.removeClass("checked");
        $(this).removeClass("fa-check-square");
      }
      else {
        $(this).css({color: "#cb223a"}).parent().css({"display": "block"});
        thumbnail.addClass("checked");
        $(this).addClass("fa-check-square");
      }
    });

    $(".thumbnail > span .fa-eye").live("click" ,function () {
      var src = $(this).parent().siblings("img").attr("src");
      var preview = $("body .preview-image");
      $("img", preview).attr("src", src);
      preview.fadeIn("slow");
    });

    $(".preview-image .close-icon").live("click", function () {
      $(this).parent().fadeOut("slow");
    });

    $(".preview-image .left").live("click", function () {
      var currentImage = $("body .preview-image img");
      var imageObj = $(".media-list img[src='"+currentImage.attr("src")+"']");
      currentImage.attr("src", $("img" ,imageObj.parents("li").prev()).attr("src"));
    });
    $(".preview-image .right").live("click", function () {
      var currentImage = $("body .preview-image img");
      var imageObj = $(".media-list img[src='"+currentImage.attr("src")+"']");
      currentImage.attr("src", $("img" ,imageObj.parents("li").next()).attr("src"));
    });

      var $tpl_select = ($('select[name="tpl"]'));
      if($tpl_select.length) {

          setTimeout(function(){
              var tpl_id = $tpl_select.val();
              if(tpl_id == 3) {
                  $('.controle-group').eq(2).show();
                  $('.controle-group').eq(3).show();
              }
              else {
                  $('.controle-group').eq(2).hide();
                  $('.controle-group').eq(3).hide();
              }
              if(tpl_id == 1) {
                  $('.control-group').eq(4).show();
                  $('.control-group').eq(3).hide();
              }
              else {
                  $('.control-group').eq(3).show();
                  $('.control-group').eq(4).hide();
              }
          }, 2000)

          $('select[name="tpl"]').live("change", function() {
              var tpl_id = $(this).val();
              if(tpl_id == 3) {
                  $('.controle-group').eq(2).show();
                  $('.controle-group').eq(3).show();
              }
              else {
                  $('.controle-group').eq(2).hide();
                  $('.controle-group').eq(3).hide();
              }
              if(tpl_id == 1) {
                  $('.control-group').eq(4).show();
                  $('.control-group').eq(3).hide();
              }
              else {
                  $('.control-group').eq(3).show();
                  $('.control-group').eq(4).hide();
              }
          });
      }


  });
  
  jQuery.easing.def = "easing";
  
})(jQuery);

// 删除内容
(function () {
  window.deleteContent = function (cid, url) {
    if (confirm(language.confirmdelete)) {
      $.ajax({
        url: window.baseurl + "/api/content/delete",
        data: {cid: cid},
        type: "POST"
      })
      .done(function () {
        //alert("删除成功");
      })
      .always(function () {
        if (typeof url != "undefined") {
          window.location.href = url;
        }
        else {
          window.location.reload();
        }
      });
    }
  }
})();

// Table pager 
(function ($) {
  $(document).ready(function () {
    var datatable = $("table.tablepager").DataTable();
    var selects = $(".filters select");
    $(".filters select").change(function () {
       datatable.search($(this).val());
      datatable.draw();
    });
  });
})(jQuery);




