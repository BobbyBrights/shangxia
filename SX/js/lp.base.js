/*
 * page base action
 */
LP.use(['jquery' ,'easing' , '../api'] , function( $ , easing , api ){
    'use strict'


    // page components here
    // ============================================================================ 
    $.easing.easeLightOutBack = function (x, t, b, c, d, s) {
        if (s == undefined) s = 0.70158;
        return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
    }


    /*
     * Animate Class
     * {@param originNumArr} 需要变化的初始化数据
     * {@param targetNumArr} 数据的最终值
     * {@param speed} 动画持续时间
     * {@param easing} 动画特效
     * {@param step} 动画每一步需要执行的了函数，主要用于更新元素的样式值，其第一个参数是个数组，数组里为数据变化的当前值
     * {@param callback} 动画结束时的回调函数
     */
    var Animate = function(originNumArr,targetNumArr,speed,easing,step,callback){
        this.queue = [];
        this.duration = speed;
        this.easing = easing;
        this.step = step;
        this.callback = callback;
        for (var i = 0; i < originNumArr.length; i++){
            this.queue.push(new Animate.fx(originNumArr[i],targetNumArr[i]));
        }
        // begin animation
        this.begin();
    }

    Animate.prototype = {
        begin: function(){
            if(this._t) return ;
            var that = this;
            this.startTime = +new Date();
            // loop
            this._t = setInterval(function(){
                var dur = +new Date() - that.startTime;
                var queue = that.queue;
                if(dur > that.duration){
                    that.end();
                    // end Animate
                    return;
                }
                var easing = Animate.easing[that.easing] || Animate.easing.linear,
                    currValues = [];
                for (var i = 0,len = queue.length; i < len; i++){
                    currValues.push(queue[i].update(dur,that.duration,easing));
                }
                // run step to update
                that.step(currValues);
            },13);
        },
        // go to end of the animation
        end: function(){
            clearInterval(this._t);
            var queue = this.queue,
                currValues = [];
            for (var i = 0,len = queue.length; i < len; i++){
                currValues.push(queue[i].target);
            }
            this.step(currValues);
            // call callback function
            this.callback && this.callback();
        },
        turnTo: function( targetNumArr ){
            clearInterval(this._t);
            var that = this;
            // reset queue
            this.startTime = + new Date();
            for (var i = 0,len = that.queue.length; i < len; i++){
                that.queue[i] = new Animate.fx(that.queue[i].current,targetNumArr[i]);
            }
            // reset interval
            this._t = setInterval(function(){
                var dur = +new Date() - that.startTime;
                var queue = that.queue;
                if(dur > that.duration){
                    that.end();
                    // end Animate
                    return;
                }
                var easing = Animate.easing[that.easing] || Animate.easing.linear,
                    currValues = [];
                for (var i = 0,len = queue.length; i < len; i++){
                    currValues.push(queue[i].update(dur,that.duration,easing));
                }
                // run step to update
                that.step(currValues);
            } , 13);
        }
    }
    //
    Animate.fx = function(origin,target){
        this.origin = origin;
        this.target = target;
        this.dist = target - origin;
    }
    Animate.fx.prototype = {
        update: function(n,duration,easing){
            var pos = easing(n/duration, n , 0 ,1 , duration);
            this.current = this.origin + this.dist * pos;
            return this.current;
        }
    }
    // easing
    Animate.easing = {
        linear: function( p, n, firstNum, diff ) {
            return firstNum + diff * p;
        },
        swing: function( p, n, firstNum, diff ) {
            return ((-Math.cos(p*Math.PI)/2) + 0.5) * diff + firstNum;
        }
    }

    
    var pageManager = (function(){
        var $header = $('.head');
        var pageInits = {
            'home-page' : function( cb ){
                var $slider = $('#home-slider');
                var slideHeight = $slider.children().height();
                $(window).scroll(function(){
                    var stTop = $(window).scrollTop();
                    var winHeight = $(window).height();

                    // fix home slider
                    var mtop = Math.min( stTop , slideHeight / 2 );
                    $slider
                        .css({
                            height: slideHeight - 2 * mtop,
                            overflow: 'hidden'
                        })
                        .find('img')
                        .css({marginTop: - mtop , marginBottom: - mtop * 2 / 3});
                    // // header fixed effect
                    if( stTop >= $header.offset().top ){
                        $header.find('.head-fixed').css('position' , 'fixed');
                    } else {
                        $header.find('.head-fixed').css('position' , 'static');
                    }
                });

                // init home page slider mouse move event
                var isMoving = false;
                var moveTimer = null;
                $('#homepage-video-slide .slideitem').mousemove(function(){
                    // judge is ithe video is playing
                    var videoObject = $(this).data('video-object');
                    clearTimeout( moveTimer );
                    if( !videoObject || videoObject.paused() )  {
                        return false;
                    }

                    var $tip = $(this).find('.slidetip');

                    if( isMoving == false ){
                        $tip.fadeIn();
                        isMoving = true;
                    }
                    moveTimer = setTimeout(function(){
                        isMoving = false;
                        $tip.stop(true).fadeOut();
                    } , 2000);
                });


                // nav-pop-item inout-effect
                $('.nav-pop-item.inout-effect').hover(function(){
                    $(this).find('span:not(.inout-bg)').fadeOut(500);
                } , function(){
                    $(this).find('span:not(.inout-bg)').fadeIn(500);
                });

                cb && cb();
            },
            'craft-page': function( cb ){
                cb && cb();
            },
            "about-page": function( cb ){
                cb && cb();
            },
            "boutique-page":function( cb ){

                $('[data-map]').each(function(){
                    // need to judge if use baidu or goole map to render the map
                    var val = $(this).data('map');
                    var first = val.split(',')[0];
                    if( first > 100 ){ // use baidu

                    } else { // use google

                    }
                });

                $('.footer .store').hide();
                console.log( $('.footer .store') );
                cb && cb();
            },
            "product-detail": function( cb ){
                var headHeight = $('.head').height();
                var $slider = $('.slide');
                var top = $slider.offset().top;
                $(window).scroll(function(){
                    var st = $(window).scrollTop();

                    if( top - st < 150 ){
                        $slider.find('.slidebox')
                            .css({
                                marginTop: ( st + 150 - top ) * 2 / 3,
                                marginBottom: - ( st + 150 - top ) * 2 / 3
                            });
                    } else {
                        $slider.find('.slidebox')
                            .css({
                                marginTop: 0,
                                marginBottom: 0
                            });
                    }
                });

                cb && cb();
            }
            
        }


        var effects = {
            'fadeup': function( $dom , index , cb ){
                $dom.delay( 150 * index )
                    .animate({
                        opacity: 1,
                        marginTop: 0
                    } , 500 )
                    .promise()
                    .then(function(){
                        cb && cb();
                    });
            }
            // 'number-rock': function( $dom , index , cb , du ){
            //     // init humbers
            //     var num = $dom.text();
            //     var $span = $('<span>' + num + '</span>').appendTo( $dom.html('').data('num' , num) );
            //     var width = $span.width();
            //     var height = $span.height();
            //     $span.css({
            //         height: height,
            //         width: width,
            //         display: 'inline-block',
            //         lineHeight: height + 'px',
            //         position: 'relative',
            //         margin: '0 auto',
            //         overflow: 'hidden',
            //         verticalAlign: 'middle'
            //     }).html('');
            //     $.each( num.split('') , function( i ){
            //         $('<div>' + "1234567890".split('').join('<br/>') + '</div>').appendTo( $span )
            //             .css({
            //                 position: 'absolute',
            //                 left: i * width / num.length,
            //                 top: -~~( Math.random() * 10 ) * height
            //             });
            //     });

            //     // run the animate
            //     var spanHeight = height;//$dom.find('span').height();

            //     var st = new Date();
            //     var duration = du || 1200;
            //     var $divs = $span.find('div');
            //     var interval = setInterval(function(){

            //         if( new Date - st >= duration ){
            //             var num = $dom.data('num');
            //             var nums = num.split('');
            //             $divs.each(function( i ){
            //                 var top = - ( nums[i] - 1 ) * spanHeight ;
            //                 if( nums[i] == 0 ){
            //                     top = - 9 * spanHeight
            //                 }
            //                 $(this).animate({
            //                     'top': top 
            //                 } , 800 , 'easeOutQuart' , function(){
            //                     if( i == nums.length - 1 ){
            //                         //$dom.html( num );
            //                         cb && cb();
            //                     }
            //                 });
            //             });
            //             clearInterval( interval );
            //             return false;
            //         }
            //         $divs.each(function(){
            //             $(this).css('top' , -( Math.random() * 10 ) * spanHeight );
            //         });
            //     } , 1000 / 15);
            // }
        }


        return {
            go: function( url , type ){
                History.pushState({
                    prev: location.href,
                    type: type
                },  undefined , url );
            },
            init: function(){
                loadingMgr.show();
                $('.footer .store').show();
                var page = $('.head').data('page');
                var fn = pageInits[ page ];

                var $allImgs = $('img');
                var $noPreLoadImgs = $('.slide .slideitem img').filter(function( index ){
                    return index > 0;
                }).add( $('.js-horizontal-slide .slide-con img').filter(function( index ){
                    return index > 2;
                }));

                var noPreLoadImgs = $noPreLoadImgs.toArray();
                $allImgs = $allImgs.filter(function( i ){
                    return $.inArray( this , noPreLoadImgs ) == -1;
                });

                if( fn ){
                    fn( function(){
                        $(window).trigger('scroll');
                        loadImages( $allImgs , loadingMgr.hide );
                    });
                } else {
                    loadImages( $allImgs , loadingMgr.hide );
                }

                // fix common page init
                // for  banpho-img
                var $footer = $('.footer');
                $(window).scroll(function(){
                    var stTop = $(window).scrollTop();
                    var winHeight = $(window).height();

                    // fix up-fadein
                    if( $('.intoview-effect').length ){
                        var index = 0;
                        $('.intoview-effect').each(function(){
                            var $dom = $(this);
                            var offTop = $dom.offset().top;
                            if( !$dom.data('init') && offTop < stTop + winHeight && offTop > stTop ){
                                $dom.data('init' , 1);

                                if( effects[ $dom.data('effect') ] ){
                                    effects[ $dom.data('effect') ]( $dom , index++ , function(){
                                        $dom.removeClass('intoview-effect');
                                    } );
                                }
                            }
                        });
                    }
                })
                .trigger('scroll');

                // init slide
                $('.slide').each(function(){
                    initSlider( $(this) );
                });


                // init horizontal slide
                $('.js-horizontal-slide').each(function(){
                    var $dom = $(this);
                    var wrapWidth = $dom.width();
                    var num = 3;
                    var $items = $dom.find('.slide-con-inner').children();

                    // 计算每一个item的宽度 
                    var $imgs = $items.find('img');
                    var totalItems = 0;
                    $imgs.each(function(){
                        totalItems += Math.round( $(this).data('width') / $(this).data('height') ) || 1;
                    });

                    var $inner = $dom.find('.slide-con-inner').width( totalItems / num * 100 + '%' );
                    var marginRight = 0.8;//parseInt( $items.css('margin-right') );
                    var counted = 0;
                    var prev = 0;
                    $items.each(function(){
                        var $this = $(this);
                        var $img = $this.find('img');
                        var indent = Math.round( $img.data('width') / $img.data('height') ) || 1;
                        counted += indent;
                        if( counted % 3 == 0 ){
                            if( indent == 1 && prev == 1 ){ // 111
                                // 第一个margin-left: 0.4%
                                // 最后一个margin-right: 0.4 %
                                $this
                                .css('marginRight' , '0.4%')
                                .prev()
                                .prev()
                                .css('marginLeft' , '0.4%' );
                            } else if( indent == 3 ) { // 1
                                $this.css({
                                    marginLeft: '0.4%',
                                    marginRight: '0.4%'
                                });
                            } else { // 21 || 12
                                $this.css('marginRight' , '0.4%')
                                    .prev()
                                    .css('marginLeft' , '0.4%');
                            }
                        }

                        $this.width( indent /  totalItems * 100 - marginRight + '%' );
                        prev = indent;
                    });

                    var total = $items.length;
                    $dom.find('.collarrowsprev').click(function(){
                        var ml = parseInt( $inner.css('marginLeft') ) || 0;
                        if( ml == 0 ) return false;
                        $inner.animate({
                            marginLeft: -Math.round( Math.abs( ml ) / $inner.parent().width() - 1 ) * 100 + '%'
                        } , 500);
                        $(window).trigger('scroll');
                    })
                    .end()
                    .find('.collarrowsnext')
                    .click(function(){
                        var ml = parseInt( $inner.css('marginLeft') ) || 0;
                        var outerWidth = $inner.parent().width();
                        if( Math.abs( ml ) >= $inner.width() - outerWidth ) return false;
                        $inner.animate({
                            marginLeft: -Math.round( Math.abs( ml ) / outerWidth + 1 ) * 100 + '%'
                        } , 500);
                        $(window).trigger('scroll');
                    });
                });

                // render map
                $('[data-map]').each(function(){
                    mapHelper.render( $(this) );
                });

                // render video
                $('[data-video-render]').each(function(){
                    var $dom = $(this).css({
                        position: 'relative',
                        overflow: 'hidden'
                    }) ;
                    var video = $dom.data('video-render');
                    var poster = $dom.find('img').attr('src');
                    renderVideo( $dom , video , poster , {pause_button: true} );
                });

                // render initHoverMoveEffect
                $('.inout-effect').each(function(){
                    initHoverMoveEffect( $(this) );
                });
                

                $(window).trigger('resize');
                return false;
            },
            destroy: function(){
                $(window).unbind('scroll');
                $(document.body).unbind('mousemove');
            }
        }
    })();

    

    var loadingMgr = (function(){
        var $loading = $('.loading-wrap');
        if( !$loading.length )
            $loading = $('<div class="loading-wrap"><div class="loading"></div></div>')
                .appendTo(document.body);
        // var positions = [-44,-142,-240,-338,-436,-534];
        // var interval = null;

        // var colors = {
        //     'black': 'rgba(0,0,0,.85)'
        // }

        var startTime = null;
        
        return {
            showLoading: function( $wrap ){
                $('<div class="loading-wrap" style="position: absolute;"><div class="loading" style="position:absolute;"></div></div>').appendTo( $wrap )
                    .fadeIn();
                var $loading = $wrap.find('.loading');
                clearInterval( $wrap.data('interval') );
                var index = 0;
                $wrap.data('interval' , setInterval(function(){
                    $loading.css('background-position' , 'right ' +  positions[ ( index++ % positions.length ) ] + 'px' );
                } , 1000 / 6 ) );
            },
            hideLoading: function( $wrap ){
                clearInterval( $wrap.data('interval') );
                $wrap.find('.loading-wrap').fadeOut();
            },
            show: function( bgcolor ){
                startTime = new Date();
                $loading.fadeIn();
                // var index = 0;
                // bgcolor = colors[bgcolor] || bgcolor || 'white';
                // var $inner = $loading.fadeIn().find('.loading');
                // $loading.css({
                //     'background-color':  bgcolor
                // });
                // clearInterval( interval );
                // interval = setInterval(function(){
                //     $inner.css('background-position' , 'right ' +  positions[ ( index++ % positions.length ) ] + 'px' );
                // } , 1000 / 6 );
            },
            hide: function(){
                console.log( 1500 - ( new Date() - startTime ) );
                // clearInterval( interval );
                setTimeout(function(){
                    $loading.fadeOut();
                } , 1700 - ( new Date() - startTime ) );
            }
        }
    })();

    function initSlider( $wrap , config ){
        var $slidebox = $wrap.find('.slidebox');
        var $slidetabs = $wrap.find('.slidetab li');
        var currentIndex = 0;
        var length = $slidebox.children().length;
        $slidebox.css( 'width' , length * 100 + '%' )
            .children()
            .css('width' , 1 / length * 100 + '%' );


        $slidetabs.click(function(){
            $(this).addClass('on')
                .siblings()
                .removeClass('on');

            var index = $(this).index();
            $slidebox.stop(true).animate({
                marginLeft: - index * 100 + '%'
            } , 400 );

            return false;
        });

        if( $wrap.data('auto') !== false ){
            setInterval(function(){
                var $next = $slidetabs.filter('.on').next();
                if( $next.length ){
                    $next.trigger('click');
                } else {
                    $slidetabs.eq(0).trigger('click');
                }
            } , 5000);
        }
    }


    function disposeVideo(){
        $(document.body).find('.video-wrap').parent()
            .each(function(){
                var video = $(this).data('video-object');
                try{video && video.dispose();}catch(e){}
                $(this).removeData('video-object').find('.video-wrap').remove();
            });
    }

    function loadImages( $img , cb ){
        var index = 0 ;
        var length = $img.length;
        if( length == 0 ){
            cb && cb();
        }
        $img.each(function(){
            $('<img/>').load(function(){
                index ++;
                if( index == length ){
                    cb && cb();
                }
            })
            .error(function(){
                index ++;
                if( index == length ){
                    cb && cb();
                }
            })
            .attr('src' , this.getAttribute('src'));
        });
    }

    function renderVideo ( $wrap , movie , poster , config , cb ){
        var id = 'video-js-' + ( $.guid++ );
        $wrap.append( LP.format( '<div class="video-wrap" style="display:none;"><video id="#[id]" style="width: 100%;height: 100%;" class="video-js vjs-default-skin"\
            preload="auto"\
              poster="#[poster]">\
             <source src="#[videoFile].mp4" type="video/mp4" />\
             <source src="#[videoFile].webm" type="video/webm" />\
             <source src="#[videoFile].ogv" type="video/ogg" />\
        </video></div>' , {id: id  , videoFile: movie , poster: poster}));

        config = $.extend( { "controls": false, "muted": false, "autoplay": false, "preload": "auto","loop": true, "children": {"loadingSpinner": false} } , config || {} );
        var ratio = config.ratio || 9/16;

        LP.use('video-js' , function(){
            var is_playing = false;
            videojs.options.flash.swf = "/js/video-js/video-js.swf";
            var myVideo = videojs( id , config , function(){
                var v = this;
                if( config.resize !== false ){
                    var resizeFn = function(){
                        var w = $wrap.width()  ;
                        var h = $wrap.height() ;
                        var vh = 0 ;
                        var vw = 0 ;
                        if( h / w > ratio ){
                            vh = h + 40;
                            vw = vh / ratio;
                        } else {
                            vw = w + 40;
                            vh = vw * ratio;
                        }

                        try{v.dimensions( vw , vh );}catch(e){}

                        $('#' + v.Q).css({
                            "margin-top": ( h - vh ) / 2,
                            "margin-left": ( w - vw ) / 2
                        });
                        return false;
                    }
                    $(window).bind( 'resize.video-' + id , resizeFn )
                        .trigger('resize.video-' + id);

                    $wrap.bind('resize.video-' + id , resizeFn);
                }
                setTimeout(function(){
                    $wrap.find('.video-wrap').fadeIn();
                    if( config.autoplay ){
                        try{myVideo.play();}catch(e){}
                    } else if( config.pause_button ){
                        $wrap.find('.vjs-big-play-button').fadeIn();
                    }
                } , 20);

                // if need to add pause button
                if( config.pause_button ){
                    if( !config.controls ){
                        $wrap.off('click.video-operation').on('click.video-operation' , function(){
                            if( is_playing ){
                                v.pause();
                            } else {
                                v.play();
                            }
                        });    
                    }
                    // add big pause btn
                    v.on('play' , function(){
                        is_playing = true;
                        $wrap.find('.vjs-big-play-button').hide();
                        var $pauseBtn = $wrap.find('.vjs-big-pause-button');
                        if( !$pauseBtn.length ){
                            $pauseBtn = $('<div class="vjs-big-pause-button"></div>').insertAfter( $wrap.find('.vjs-big-play-button') )
                                .click(function(){
                                    v.pause();
                                });
                        }
                        $pauseBtn.show()
                            .delay( 4000 )
                            .fadeOut();
                    });

                    v.on('pause' , function(){
                        is_playing = false;
                        $wrap.find('.vjs-big-pause-button').hide();
                        $wrap.find('.vjs-big-play-button').fadeIn();
                    });
                }


                $wrap.data('video-object' , v);

                cb && cb(v);
            } );
        });
    }

    /* init hover effect */
    function initHoverMoveEffect( $dom , hoverin , hoverout ){
        if( $dom.data('initHoverMoveEffect') ) return;
        $dom.data('initHoverMoveEffect' , 1);
        var $bg = $dom.find('.inout-bg');
        $dom.css('position','relative');

        $dom.hover(function( ev ){
            var width = $dom.width();
            var height = $dom.height();

            var off = $dom.offset();
            var topOff = ev.pageY - off.top;
            var leftOff = ev.pageX - off.left;
            var bottomOff = height + off.top - ev.pageY;
            var rightOff = width + off.left - ev.pageX;

            var min = Math.min( topOff , leftOff , bottomOff , rightOff );
            var ori = null;
            var tar = null;
            if( min == topOff ){ // from top 
                ori = { left: 0,top: '-100%'};
                tar = { top: 0 };
            } else if( min == leftOff ){
                ori = { left: '-100%',top: 0};
                tar = { left: 0 };
            } else if( min == bottomOff ){
                ori = { left: 0,top: '100%'};
                tar = { top: 0 };
            } else {
                ori = { left: '100%',top: 0};
                tar = { left: 0 };
            }
            $bg.css( ori ).stop( true )
                .animate( tar , 500 );

            hoverin && hoverin();
        } , function( ev ){
            var width = $dom.width();
            var height = $dom.height();

            var off = $dom.offset();
            var topOff = ev.pageY - off.top;
            var leftOff = ev.pageX - off.left;
            var bottomOff = height + off.top - ev.pageY;
            var rightOff = width + off.left - ev.pageX;

            var min = Math.min( topOff , leftOff , bottomOff , rightOff );
            var ori = null;
            var tar = null;
            if( min == topOff ){ // from top 
                ori = { left: 0,top: '-100%'};
                tar = { top: 0 };
            } else if( min == leftOff ){
                ori = { left: '-100%',top: 0};
                tar = { left: 0 };
            } else if( min == bottomOff ){
                ori = { left: 0,top: '100%'};
                tar = { top: 0 };
            } else {
                ori = { left: '100%',top: 0};
                tar = { left: 0 };
            }
            $bg.stop( true )
                .animate( ori , 500 );

            hoverout && hoverout();
        });
    }

    var mapHelper = (function(){
        return {
            render: function( $dom ){
                var point = $dom.data('map').split(',');
                if( $dom.data('baidu') ){ // use baidu
                    this.renderBaidu( $dom , point );
                } else {
                    this.renderGoogle( $dom , point );
                }
                $dom.removeAttr('data-map');
            },
            renderBaidu: function( $dom , point ){
                // var html = '<img class="map-marker" src="#[markerPath]" />\
                //     <img src="http://api.map.baidu.com/staticimage?center=#[pointer]&width=#[width]&height=#[height]&zoom=11" />';
                // $dom.html( LP.format( html , {
                //     markerPath: SOURCE_PATH + '/images/map-marker.png',
                //     pointer: point.join(','),
                //     width: $dom.width(),
                //     height: $dom.height()
                // } ) );

                var id = $dom.attr('id') || 'baidu-map-' + ( + new Date() );
                $dom.attr( 'id' , id ) ;
                if( !window.BMap ){
                    var _LP = window.LP;
                    LP.use('http://api0.map.bdimg.com/getscript?v=2.0&ak=AwxxvHue9bTdFietVWM4PLtk&services=&t=20140725172530' , function(){
                        window.LP = _LP;
                    });
                }
                var interval = setInterval(function(){
                    if( window.BMap ){
                        clearInterval( interval );
                        var oMap = new BMap.Map( id );
                        oMap.addControl(new BMap.NavigationControl());
                        point = new BMap.Point( point[0] , point[1] );
                        oMap.centerAndZoom(point, 15);
                        oMap.setMapStyle({
                          styleJson:[
          {
                    "featureType": "all",
                    "elementType": "geometry.fill",
                    "stylers": {
                              "lightness": 13,
                              "saturation": -100
                    }
          },
          {
                    "featureType": "road",
                    "elementType": "geometry.stroke",
                    "stylers": {
                              "color": "#cac4b5"
                    }
          },
          {
                    "featureType": "building",
                    "elementType": "all",
                    "stylers": {
                              "color": "#9e927a"
                    }
          },
          {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": {
                              "color": "#9e927a"
                    }
          },
          {
                    "featureType": "administrative",
                    "elementType": "labels.text.stroke",
                    "stylers": {
                              "color": "#ffffff",
                              "saturation": 100
                    }
          },
          {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": {
                              "color": "#9e927a"
                    }
          },
          {
                    "featureType": "road",
                    "elementType": "labels.text.stroke",
                    "stylers": {
                              "color": "#ffffff"
                    }
          }
]
                        });

                        var myIcon = new BMap.Icon("../SX/images/marker.png", new BMap.Size(34,40));
                        var marker2 = new BMap.Marker(point,{icon:myIcon});  // 创建标注
                        oMap.addOverlay(marker2);              // 将标注添加到地图中
                    }
                } , 100 );
            },
            renderGoogle: function( $dom , point ){
                point[0] = point[0] || 0;
                point[1] = point[1] || 0;


                var map = new google.maps.Map($dom[0],{
                    center: new google.maps.LatLng(point[0],point[1]),
                    zoom:5,
                    mapTypeId:google.maps.MapTypeId.ROADMAP
                });


                var styleArray = [
                  {
                    featureType: "all",
                    stylers: [
                      { saturation: -80 }
                    ]
                  },{
                    featureType: "road.arterial",
                    elementType: "geometry",
                    stylers: [
                      { hue: "#4b3700" },
                      { saturation: 50 }
                    ]
                  },{
                    featureType: "all",
                    elementType: "labels.text.stroke",
                    stylers: [
                      { hue: "#4b3700" },
                      { saturation: 50 }
                    ]                
                  }
                ];
                map.setOptions({styles: styleArray});

                new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(point[0],point[1]),
                    icon: "../SX/images/marker.png"
                });

                // var map = new google.maps.Map($dom[0],{
                //     center: new google.maps.LatLng(point[0],point[1]),
                //     zoom:5,
                //     mapTypeId:google.maps.MapTypeId.ROADMAP
                // });

                // new google.maps.Marker({
                //     map: map,
                //     position: new google.maps.LatLng(point[0],point[1])
                //   });
            }
        }
    })();


    var popHelper = (function(){

        return {

        }
    })();

    // page init here
    // ==============================================================================

    // init map
    // var _LP = window.LP;
    // LP.use('http://api0.map.bdimg.com/getscript?v=2.0&ak=AwxxvHue9bTdFietVWM4PLtk&services=&t=20140725172530' , function(){
    //     window.LP = _LP;
    // });
    // var interval = setInterval(function(){
    //     if( window.BMap ){
    //         clearInterval( interval );
    //         var oMap = new BMap.Map("map");
    //         oMap.addControl(new BMap.NavigationControl());
    //         var point = new BMap.Point(121.478988,31.227919);
    //         oMap.centerAndZoom(point, 15);
    //         //oMap.setMapStyle({style: 'grayscale'});
    //         // oMap.setMapStyle({
    //         //   styleJson:[{
    //         //     "featureType": "all",
    //         //     "elementType": "all",
    //         //     "stylers": {
    //         //       "lightness": 13,
    //         //       "saturation": -100
    //         //     }
    //         //   }]
    //         // });
    //     }
    // } , 100 );

    // var map=new google.maps.Map(document.getElementById( 'google-map' ),{
    //     center:new google.maps.LatLng(51.508742,-0.120850),
    //     zoom:5,
    //     mapTypeId:google.maps.MapTypeId.ROADMAP
    // });

    $(window).resize(function(){
        // fix height
        // $('.knowhowintro').each(function(){
        //     var h = $(this).parent('.knowhowitem').children('.knowhowpic').height()
        //     $(this).css('padding-top' , (h - $(this).height())/2)
        // })
        $('.knowhowintro').each(function(){
            var h = $(this).parent('.knowhowitem').children('.knowhowpic').height()
            $(this).css('height' , h)
            $(this).children('p').css('padding-top' , (h - $(this).children('p').height())/2)
        })
        $('.proinfortxt').each(function(){
            var h = $(this).next('.proinforpic').height();
            $(this).height( h );
            $(this).find('.proinfortxt-inner').height( h - 100 )
                .css('overflow' , 'hidden');
        });

        $('.aboutinfortxt').each(function(){
            var h = $(this).next('.proinforpic').height();
            $(this).height( h - 80 )
                .find('p').height( h - 240 );
        });
    })
    .keyup(function( ev ){
        switch( ev.which ){
            case 27:
                var dom = $('.pop .popclose').get(0);
                dom && dom.click();
                break;
        }
    });
    // change history
    LP.use('../plugin/history.js' , function(){
        History.replaceState( { prev: '' } , undefined , location.href  );
        pageManager.init( );

        $(document).ajaxError(function(){
            loadingMgr.hide();
        });
        // Bind to StateChange Event
        History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate

            var State = History.getState(); // Note: We are using History.getState() instead of event.state
            var prev = State.data.prev;
            var type = State.data.type;

            // if only change hash
            if( State.url.indexOf('##') >= 0 ){
                return false;
            }
            // show loading
            loadingMgr.show();
            switch( type ){
                default: 
                    $.get( location.href , '' , function( html ){
                        var $newPage = $('<div>' + html + '</div>');
                        html = $newPage.find('.wrap')
                            .find('.footer')
                            .remove()
                            .end()
                            .html();
                        $( '.wrap' ).children(':not(.footer)').animate({
                            opacity: 0
                        } , 500);

                        setTimeout(function(){
                            $( '.wrap' ).children(':not(.footer)')
                                .remove()
                                .end()
                                .prepend( html )
                                .children(':not(.nav-mask)')
                                .fadeIn();
                            //pagetitarrbottom

                            $('html,body').animate({
                                scrollTop: 0
                            } , 300 );
                            pageManager.destroy( );
                            pageManager.init( );
                        } , 500);
                    });
            }
        });
    });

    



    // page actions here
    // ============================================================================
    LP.action('nav-pop' , function(){
        
        var text = $.trim( $(this).text() ).toLowerCase();
        var $inner = $(this).closest('.head-inner');
        $inner.attr('class' , 'head-inner cs-clear active-' + text );
        
        if( $('.nav-pop-' + text ).is(':visible') ){
            LP.triggerAction('nav-mask');
            return false;
        }
        $('.head .nav li').removeClass('active');
        $(this).closest('li').addClass('active');

        $('.nav-pop').fadeOut();
        $('.nav-pop-' + text ).stop(true , true).fadeIn();
        $('.nav-mask').fadeIn();
        return false;
    });

    LP.action('nav-mask' , function(){
        var $inner = $('.head-inner').attr('class' , 'head-inner cs-clear');
        $('.head-inner').find('li.active').removeClass('active');
        $('.nav-pop').fadeOut();
        $('.nav-mask').fadeOut();

        return false;
    });

    LP.action('nav-link' , function(){
        disposeVideo();
        // load next page
        var href = $(this).attr('href');
        pageManager.go( href );

        return false;
    });

    LP.action('show-search-form' , function(){
        if( $('.searchform').is(':hidden') ){
            $('.searchform').fadeIn()
                .find('input')
                .focus();
        } else {
            $('.searchform').fadeOut();
        }
        
        return false;
    });


    LP.action('render-foot-map' , function( data ){
        switch( data.type ){
            case 'beijing':
                $('#map').css('z-index' , 2);
                $('#google-map').css('z-index' , 1);
                break;
            case 'paris':
                $('#map').css('z-index' , 1);
                $('#google-map').css('z-index' , 2);
                break;
        }

        return false;
    });
    

    LP.action('homepage-watch-video' , function(){
        var $dom = $(this);
        var $tip = $dom.closest('.slidetip');
        
        var index = $tip.next().find('.on').index();
        var $li = $tip.prev().children().eq( index );
        var stopHtml = 'Watch video<br><br>Watch video';
        var playHtml = 'Pause video<br><br>Pause video';

        if( $li.find('.video-wrap').length ){
            var obj = $li.data('video-object');
            var isPaused = obj.paused();
            obj[ isPaused ? 'play' : 'pause' ]();

            $dom.find('i').html( isPaused ? playHtml : stopHtml );
        } else {
            disposeVideo();
            var video = $li.data('video');
            renderVideo( $li , video , $li.find('img').attr('src') , {autoplay: true} );
            $dom.find('i').html( playHtml );
        }

        return false;
    });


    LP.action('page-prev' , function(){
        var href = location.href;
        var $links = $('.sitelinkitem a');
        $links.each(function( i ){
            var h = $(this).attr('href');
            h = h.replace(/.\/|..\//g , '');
            if( href.indexOf( h ) >= 0 ){
                var $link = $links.eq( i - 1 );
                $link[0] && $link[0].click();
                return false;
            }
        });
    });

    LP.action('page-next' , function(){
        var href = location.href;
        var $links = $('.sitelinkitem a');
        $links.each(function( i ){
            var h = $(this).attr('href');
            h = h.replace(/.\/|..\//g , '');
            if( href.indexOf( h ) >= 0 ){
                var $link = $links.eq( i + 1 );
                $link[0] && $link[0].click();
                return false;
            }
        });
    });


    LP.action('pop-jobs' , function(){
        var html = $(this).next().val();
        $(html).appendTo( document.body )
            .hide()
            .fadeIn();

        return false;
    });

    LP.action('popclose' , function(){
        var $pop = $(this).closest('.pop').fadeOut( function(){
            $(this).remove();
        } );
        $pop.prev('.popshade').fadeOut( function(){
            $(this).remove();
        } );
        return false;
    });

    LP.action('home-collarrowsprev' , function(){
        var $slider = $(this).parent();
        var $li = $slider.find('.slidetab li.on');
        var $prev = $li.prev();
        $prev.length ? $prev.trigger('click') : $slider.find('.slidetab li').last().trigger('click');
        // change the tit
        var index = $li.index() - 1;
        if( !$prev.length ){
            index = $slider.find('.slidebox li').length - 1;
        }
        var $item = $slider.find('.slidebox li').eq( index );

        $slider.find('.slidetip2-tit').html( $item.data('tit') )
            .end()
            .find('.slidetip2-index')
            .html( ( index + 1 ) + '/' + $slider.find(".slidebox").children().length );

        return false;
    });

    LP.action('home-collarrowsnext' , function(){
        var $slider = $(this).parent();
        var $li = $slider.find('.slidetab li.on');
        var $next = $(this).parent().find('.slidetab li.on').next();
        $next.length ? $next.trigger('click') : $(this).parent().find('.slidetab li').first().trigger('click');

        // change the tit
        var index = $li.index() + 1;
        if( !$next.length ){
            index = 0;
        }
        var $item = $slider.find('.slidebox li').eq( index );

        $slider.find('.slidetip2-tit').html( $item.data('tit') )
            .end()
            .find('.slidetip2-index')
            .html( ( index + 1 ) + '/' + $slider.find(".slidebox").children().length );
        return false;
    });

    LP.action('pop-press-item' , function(){
        var tpl = '<div class="popshade"></div>\
                <div class="pop">\
                    <div class="popclose" data-a="popclose"></div>\
                    <div class="poppiccon">\
                        <div class="picoperate cs-clear">\
                            <a href="#" class="picopsized"></a>\
                            <a href="#" class="picopsizeup"></a>\
                            <a href="#" class="picopdown"></a>\
                        </div>\
                        <img src="#[img]" alt="">\
                    </div>\
                </div>';
        var html = LP.format( tpl , {img: $(this).data('press')} );
        $(html).appendTo( document.body ).hide().fadeIn();

        return false;
    });

    LP.action('newsletter' , function(){
        var tpl = $('#newsletter').html();
        $(tpl).appendTo( document.body )
            .hide()
            .fadeIn();
        return false;
    });


    LP.action('craft-read' , function(){
        $( $(this).siblings('textarea').val() )
            .appendTo( document.body )
            .hide()
            .fadeIn();
        return false;
    });
});
