/*
 * page base action
 */
LP.use(['jquery' ,'easing' , '../api'] , function( $ , easing , api ){
    'use strict'
    var isMobile = !!navigator.userAgent.toLowerCase().match(/iphone os|midp|rv:1.2.3.4|android|windows ce|windows mobile/i);
    var isIphone = !!navigator.userAgent.toLowerCase().match(/iphone os/i);
    var isTablet = !!navigator.userAgent.toLowerCase().match(/(ipad|android(?!.*mobile)|tablet)/i);

    String.prototype.subByte = function (start, bytes)
    {
        for (var i=start; bytes>0; i++)
        {
            var code = this.charCodeAt(i);
            bytes -= code<256 ? 1 : 2;
        }
        return this.slice(start,i)
    }

    if( isTablet ){
        $('html').addClass('tablet');
        $('meta[name="viewport"]').attr('content','minimal-ui, width=768, minimum-scale=0.7, maximum-scale=0.7, target-densityDpi=290,user-scalable=no');
    }
    if( isMobile ){
        $(document.body).addClass('mobile');
    }
    // LP.use('smoothscroll' , function(){
    //     $('html,body').smoothScroll();
    // });

    // page components here
    // ============================================================================ 
    $.easing.easeLightOutBack = function (x, t, b, c, d, s) {
        if (s == undefined) s = 0.70158;
        return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
    }

    var loadPressData = function( year , page , cb ){
        var tpl = '<a href="#" data-cid="#[cid]" data-a="show-pop" data-d="press=1" class="prolistitem newsitem intoview-effect" style="position:relative" data-effect="fadeup">\
                <img src="#[press_image]" width="100%" />\
                <p>#[title]<br /><span class="date">#[date]</span></p>\
                #[video_btn]\
                <textarea style="display:none;">\
                    #[content]\
                        <a href="#" class="picopsized" data-a="picopsized"></a>\
                        <a href="#" class="picopsizeup" data-a="picopsizeup"></a>\
                        <a href="#[master_image]" class="picopdown" target="_blank"></a>\
                    </div>\
                    <div class="pic-press">\
                        <img src="#[master_image]" width="100%" style="margin:0 auto;">\
                    </div>\
                  </textarea>\
              </a>';

        var conVideo = '<div class="press-video" style="position:fixed;width:100%;height:100%;left:0;top:0;" data-video="#[video]"></div>';
        var conImage = '<div class="picoperate cs-clear">\
                    <a href="#" class="picopsized" data-a="picopsized"></a>\
                    <a href="#" class="picopsizeup" data-a="picopsizeup"></a>\
                    <a href="#[master_image]" class="picopdown" target="_blank"></a>\
                </div>\
                <div class="pic-press">\
                    <img src="#[master_image]" width="100%" style="margin:0 auto;">\
                </div>';



        $.get('/admin/api/content/press?year=' + year + '&page=' + page , function( e ){
            var list = e.data;
            if( !list || !list.length ){
                cb && cb();
                return;
            }
            var aHtml = [];
            $.each( list , function( i , press ){
                
                press.date = press.publish_date;

                press.content = LP.format( press.video ? conVideo : conImage , press );
                press.video_btn = press.video ? '<div class="press-video-play"></div>' : '';
                aHtml.push( LP.format( tpl , press ) );
            });

            var $dom = $('.press-list[data-year="' + year + '"]');
            $dom.data('page' , page);
            $dom.append( aHtml.join('') );
            if( list.length >= 15 ){
                $dom.data('more-press' , true);
                $('[data-a="loadmore-press"]').show();
            } else {
                $dom.data('more-press' , false);
                $('[data-a="loadmore-press"]').hide();
            }

            // set other's item height
            $('[data-cid="' + list[0].cid + '"]').find('img')
                .load(function(){
                    $(window).trigger('resize');
                });

            $(window).trigger('scroll');

            cb && cb();
        } , 'json');
    }
    
    var pageManager = (function(){
        var $header = $('.head');
        var pageInits = {
            'home-page' : function( cb ){
                var $slider = $('#home-slider');
                var slideHeight = $slider.children().height();
                var $header = $('.head');
                if( !isMobile ){
                    $(window).scroll(function(){
                        var stTop = $(window).scrollTop();
                        var winHeight = $(window).height();
                        
                        // // header fixed effect
                        if( stTop >= $header.offset().top ){
                            $header.find('.head-fixed').css('position' , 'fixed');
                        } else {
                            $header.find('.head-fixed').css('position' , 'static');
                        }
                    });
                }


                // init home page slider mouse move event
                if( !isMobile ){
                    var isMoving = false;
                    var moveTimer = null;
                    $('#homepage-video-slide').mousemove(function(){
                        // judge is ithe video is playing
                        var $item = $(this).find('.slideitem').eq( $(this).find('.slidetab li.on').index() );
                        var $tip = $('#homepage-video-slide').find('.slidetip');
                        var videoObject = $item.data('video-object');
                        clearTimeout( moveTimer );
                        if( !videoObject || videoObject.paused() )  {
                            $tip.stop(true , true).fadeIn();
                            return false;
                        }

                        if( isMoving == false ){
                            $tip.stop(true , true).fadeIn();
                            isMoving = true;
                        }
                        moveTimer = setTimeout(function(){
                            isMoving = false;
                            $tip.stop(true , true).fadeOut();
                        } , 2000);
                    });

                    $('#homepage-video-slide .slidetab li').click(function(){
                        var index = $(this).index();
                        var $item = $('#homepage-video-slide .slideitem').eq( index );
                        $('#homepage-video-slide').find('.slidetip2-tit').html( $item.data('tit') )
                            .end()
                            .find('.slidetip2-index')
                            .html( ( index + 1 ) + '/' + $("#homepage-video-slide  .slidebox").children().length );
                    });
                } else {
                    var $dom = $('#homepage-video-slide li').eq(0);
                    renderVideo( $dom , $dom.data('mp4') , $dom.data('webm') , $dom.find('img').attr('src') , {pause_button: true} , function(){
                        $dom.find('.video-wrap').css('zIndex',1);
                    } );
                }

                cb && cb();
            },
            'craft-page': function( cb ){
                $('.knowhowintro,.related-product').each(function(){
                    $(this).insertAfter( $(this).next() );
                });

                $('.coll_product .arrows').css({
                    height: $('.coll_product .slide-con').height(),
                    marginTop: $('.coll_product h2').height()
                });

                cb && cb();
            },
            "about-page": function( cb ){
                if( isMobile ){
                    setTimeout(function(){
                        $('.aboutinfortxt').insertAfter($('.proinforpic'));
                        $('.knowhowintro').each(function(){
                            $(this).insertAfter( $(this).next() );
                        });
                    } , 1000);
                }
                cb && cb();
            },
            "boutique-page":function( cb ){
                $('.footer .store-wrap').hide();
                $('.findus .storemap').insertBefore( $('.findus .storechoose2') );
                cb && cb();
            },
            "product-detail": function( cb ){
                var headHeight = $('.head').height();
                var $slider = $('.slide');
                var top = $slider.offset().top;
                // $(window).scroll(function(){
                //     var st = $(window).scrollTop();

                //     if( top - st < headHeight ){
                //         $slider.find('.slidebox')
                //             .css({
                //                 marginTop: ( st + headHeight - top ) * 2 / 3,
                //                 marginBottom: - ( st + headHeight - top ) * 2 / 3
                //             });
                //     } else {
                //         $slider.find('.slidebox')
                //             .css({
                //                 marginTop: 0,
                //                 marginBottom: 0
                //             });
                //     }
                // });

                cb && cb();
            },
            "news-press": function( cb ){
                var $years = $('.newsoldertime').find('li');
                var lastYear = $years.last().trigger('click').html();
                loadPressData( lastYear , 1 , cb);
                // bind event
                $('.newsoldertime li').click(function(){
                    var year = $.trim( $(this).html() );
                    var $dom = $('.press-list[data-year="' + year + '"]');
                    if( !$dom.children().length ){
                        loadPressData( year , 1 );
                    }
                });
            },
            "news": function( cb ){
                // init news-picinfortxt
                cb && cb();
            },
            "contact-page": function( cb ){
                if( isMobile ){
                    $('.knowhowintro').each(function(){
                        $(this).insertAfter( $(this).next() );
                    });
                }
                $('input[type="file"]').change(function(){
                    var fileName = this.value.replace(/.*?([^\/\\]+)$/,'$1');
                    $(this).parent().siblings('span').remove();
                    $('<span></span>').appendTo( this.parentNode.parentNode )
                        .html( fileName )
                        .css({
                            position: 'absolute',
                            whiteSpace: 'nowrap',
                            right: 30,
                            bottom: 3,
                            color: '#4b3700'
                        });
                });

                $('input[name="name"],input[name="email"],textarea[name="message"]').keyup(function(){
                    $(this).prev().find('.error').html('');
                });

                $('textarea[name="message"]').keyup(function(){
                    var len = $(this).data('max-length');
                    var tip = $(this).data('max-length-tip');
                    if( this.value.length > len ){
                        $(this).prev().find('.error').html(tip);
                    } else {
                        $(this).prev().find('.error').html('');
                    }
                });

                cb && cb();
            }
        }


        var effects = {
            'fadeup': function( $dom , index , cb ){
                var delay = $dom.data('effect-delay') || 0;
                var tarMarginTop = parseInt( $dom.css('marginTop') ) || 0;
                var marginBottom = parseInt( $dom.css('marginBottom') ) || 0;
                $dom
                    .css({
                        marginTop: tarMarginTop + 150 ,
                        marginBottom: marginBottom - 150
                    })
                    .delay( 150 * index + delay )
                    .animate({
                        opacity: 1,
                        marginTop: tarMarginTop,
                        marginBottom: marginBottom
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

        var normalPageLoading = function( $allImgs ){
            var startAnimate = false;
            $('.loading')
                .css({
                    top: 23,
                    left: '50%',
                    marginLeft: -51
                });


            loadImages( $allImgs , function(){
                loadingMgr.hide( function(){
                    $(window).trigger('resize')
                        .scrollTop(0);
                    window.LOADING = false;
                } );
            } , function( index , total ){
                loadingMgr.process( index , total , function( percent ){
                    // if( isFirstLoading ){
                    //     if( !startAnimate && percent > 0.7 ){
                    //         startAnimate = true;
                    //         $('.nav li ,.hd_oter')
                    //             .each(function( i ){
                    //                 $(this).css({
                    //                     opacity: 0,
                    //                     marginTop: 30
                    //                 }).delay( i * 80 )
                    //                 .animate({
                    //                     opacity: 1,
                    //                     marginTop: 0
                    //                 } , 300 , 'easeLightOutBack');
                    //             });
                    //         isFirstLoading = false;
                    //     }
                    // }
                });
            });
        }

        var homePageLoading = function( $allImgs ){
            // disable scroll event
            var startAnimate = false;

            var left = $('.logo a').offset().left;
            $('.loading').css({
                left: left,
                marginLeft: -28,
                marginTop: -67
            });

            loadImages( $allImgs , function(){
                loadingMgr.hide( function(){
                    var $cloneHeader = $('.loading-wrap .head-inner-wrap')
                        .animate({
                            top: 0,
                            marginTop: -headHeight
                        } , 800);

                    var winHeight = $(window).height();
                    var winWidth = $(window).width();
                    var sliderHeight = isTablet ? 950 / 1600 * winWidth :  winHeight - headHeight;

                    // set slider css
                    var $homeSilder = $('#home-slider')
                        .css({
                            height: isMobile ? 'auto' : sliderHeight,
                            overflow: 'hidden'
                        })
                        .find('.slide')
                        .css({
                            height: '100%',
                            marginTop: -sliderHeight
                        })
                        .find('.slidetip,.slidetab')
                        .css({
                            opacity: 0,
                            bottom: 0
                        })
                        .end();

                    var $loadingWrap = $('.loading-wrap');
                    $loadingWrap.stop(true)
                        .animate( isTablet ? {
                            top: sliderHeight,
                            opacity: 0
                        } : { top: '100%' } , 800)
                        .promise()
                        .then(function(){
                            // run the animate
                            $homeSilder
                                .animate({
                                    marginTop: 0
                                } , 500)
                                .promise()
                                .then(function(){
                                    $('#home-slider').width($('#home-slider').width() - 1).width($('#home-slider').width()+ 1);
                                    $('#home-slider').css('width', '100%');

                                    $(this).find('.slidetip')
                                        .css('top' , 'auto')
                                        .animate({
                                            opacity: 1,
                                            bottom: '20%'
                                        } , 300)
                                        .end()
                                        .find('.slidetab')
                                        .animate({
                                            opacity: 1,
                                            bottom: 50
                                        } , 300);
                                });

                            $loadingWrap.fadeOut( function(){
                                $loadingWrap.removeAttr('style')
                                    .find('.loading')
                                    .appendTo( $loadingWrap )
                                    .removeAttr('style')
                                    .prev()
                                    .remove();

                                if( isMobile ){
                                    $('.head-fixed').removeAttr('style');
                                }
                            } );
                        });
                    
                    $(window).trigger('resize')
                        .scrollTop(0);

                    window.LOADING = false;
                } );
            } , function( index , total ){
                loadingMgr.process( index , total , function( percent ){
                    if( !startAnimate && percent > 0.7 ){
                        startAnimate = true;
                        $(window).scrollTop(0);
                        //transform
                        var $cloneHeader = $('.head-inner-wrap').clone()
                            .find('.logo').show()
                            .end()
                            .find('.logosmall').hide()
                            .end()
                            .appendTo( $('.loading-wrap') )
                            .css({
                                marginTop: -63,
                                top: '66%'
                            })
                            // hide logo
                            .find('.logo a')
                            .css('background' , 'none')
                            .end()
                            // animate other menus
                            .find('.nav li ,.hd_oter')
                            .each(function( i ){
                                $(this).css({
                                    opacity: 0,
                                    marginTop: 30
                                }).delay( i * 80 )
                                .animate({
                                    opacity: 1,
                                    marginTop: 0
                                } , 300 , 'easeLightOutBack');
                            })
                            .end();

                        $('.loading-wrap .loading')
                            .appendTo( $cloneHeader.find('.logo a') )
                            .css({
                                marginTop: -50,
                                top: 23,
                                left: '50%',
                                marginLeft: -53
                            });
                    }
                } );
            });
        }

        var isFirstLoading = true;

        return {
            gotoPage: function( url , type ){
                var urls = url.split('#');
                History.pushState({
                    prev: location.href,
                    hash: urls[1],
                    type: type
                },  undefined , urls[0] );
            },
            init: function(){
                window.LOADING = true;
                loadingMgr.start();

                $('.footer .store-wrap').show();
                var page = $('.head').data('page');
                var fn = pageInits[ page ];

                var $allImgs = $('img');

                var $noPreLoadImgs = $('.slide .slideitem img').filter(function( index ){
                    return index > 0;
                }).add( $('.js-horizontal-slide .slide-con img').filter(function( index ){
                    return index > 2;
                }));

                if( isMobile ){
                    $('.js-horizontal-slide').attr('data-num' , 1);

                    // init swap
                    LP.use('hammer' , function(){
                        $(".slidebox > *")
                           .hammer()
                           .on("swipeleft",function(ev){
                                var $slidetab = $(this).closest('.slide').find('.slidetab');
                                if( $slidetab.length ){
                                    $slidetab.find('.on').next().trigger('click');
                                }
                           })
                           .on("swiperight" , function( ev ){
                                var $slidetab = $(this).closest('.slide').find('.slidetab');
                                if( $slidetab.length ){
                                    $slidetab.find('.on').prev().trigger('click');
                                }
                           });

                        $('.js-horizontal-slide .slide-con-inner > *').hammer()
                           .on("swipeleft",function(ev){
                                $(this).closest('.js-horizontal-slide').find('.collarrowsnext').trigger('click');
                           })
                           .on("swiperight" , function( ev ){
                                $(this).closest('.js-horizontal-slide').find('.collarrowsprev').trigger('click');
                           });
                    });
                    

                    // render nav
                    var $navWrap = $('.nav-pop-collections .nav-pop-wrap').eq(0).appendTo('.nav-pop-collections');
                    var $gift = $('.nav-pop-nav p a').last();
                    // $navWrap.find('.nav-pop-wrap-inner a').last().clone()
                    //     .attr( 'href' , $gift.attr('href') )
                    //     .find('.nav-text i')
                    //     .html( $gift.text().replace('>' , '') )
                    //     .end()
                    //     .appendTo( $navWrap.find('.nav-pop-wrap-inner') );
                }


                var noPreLoadImgs = $noPreLoadImgs.toArray();
                $allImgs = $allImgs.filter(function( i ){
                    return $.inArray( this , noPreLoadImgs ) == -1;
                });

                if( fn ){
                    fn( function(){
                        if( page == 'home-page' ){
                            homePageLoading( $allImgs );
                            // if( isMobile ){
                            //     normalPageLoading( $allImgs );
                            // } else  {
                            //     homePageLoading( $allImgs );
                            // }
                        } else {
                            normalPageLoading( $allImgs );
                        }
                    });
                } else {
                    normalPageLoading( $allImgs );
                }

                // fix common page init
                // for  banpho-img
                var $footer = $('.footer');
                $(window).scroll(function(){
                    var stTop = $(window).scrollTop();
                    var headHeight = $('.head').height();
                    var winHeight = $(window).height();

                    // fix up-fadein
                    if( $('.intoview-effect').length ){
                        var index = 0;
                        $('.intoview-effect').each(function(){
                            var $dom = $(this);
                            var offTop = $dom.offset().top;
                            var domHeight = $dom.height();
                            if( !$dom.data('init') && ( offTop - winHeight < stTop && offTop + domHeight > stTop ) ){
                                $dom.data('init' , 1);

                                if( effects[ $dom.data('effect') ] ){
                                    effects[ $dom.data('effect') ]( $dom , index++ , function(){
                                        $dom.removeClass('intoview-effect');
                                    } );
                                }
                            }
                        });
                    }

                    // fix $('.scroll-lowheight')
                    if( $('.scroll-lowheight') ){
                        $('.scroll-lowheight').css('overflow','hidden').each(function(){
                            var $dom = $(this);
                            if( !$dom.data('height') ){
                                $dom.data('height' , $dom.height());
                            }
                            var domHeight = $dom.height();//$dom.data('height');


                            var $item = $dom.find('.scroll-lowheight-item');
                            var exHeight = $item.height() - domHeight;
                            var off = $dom.offset();
                            var winHeight = $(window).height();

                            var percent = ( off.top - stTop - headHeight + domHeight ) / ( winHeight - headHeight + domHeight );
                            percent = Math.max( 0 , percent );
                            percent = Math.min( 1 , percent );
                            $item.css({
                                marginTop: -exHeight * ( 1 - percent )
                            });

                            // if( stTop > off.top - headHeight ){
                            //     $item.css({
                            //         marginBottom: -( stTop + headHeight - off.top ) / 2
                            //     });
                            // } else {
                            //     $item.css({
                            //         //marginTop: 0,
                            //         marginBottom: 0
                            //     });
                            // }
                        });
                    }


                    // fix header effect
                    var headerOff = $('.head').offset().top;
                    var percentOff = stTop - headerOff;
                    percentOff = Math.max( percentOff , 0 );
                    percentOff = Math.min( percentOff , 48 );
                    $('.head-inner').css('padding' , 24 - (percentOff) / 2 + 'px 0' );
                    var scale =  ( ( 1 - ( percentOff ) / 48 ) * 0.3 + 0.7 );

                    //scale= parseInt(scale * 10)/10;

                    if(scale == 0.7){
                        $('.head .logo').hide();
                        $('.head .logosmall').show();
                    }else{
                        $('.head .logo').show();
                        $('.head .logosmall').hide();
                    }
                    scale = 'scale(' + scale + ')';
                    $('.logo').css({
                        'transform' : scale,
                        '-webkit-transform' : scale,
                        '-moz-transform' : scale,
                        '-ms-transform' : scale,
                        '-o-transform' : scale
                    });

                    $('.hd_oter').css('top' , ( 1 - ( percentOff ) / 48 ) * 25 + 22 );
                })
                .trigger('scroll');

                // init slide
                $('.slide').each(function(){
                    initSlider( $(this) );
                });



                // =====================================================================================
                // init horizontal slide
                initHoriSlider( $('.js-horizontal-slide') );

                
                // render map
                $('[data-map]').each(function(){
                    mapHelper.render( $(this) );
                });

                // render video
                $('[data-video-render]').each(function(){
                    // fix video image
                    var $dom = $(this).css({
                        position: 'relative',
                        overflow: 'hidden'
                    });

                    fixImageToWrap( $dom , $dom.find('img') );

                    var poster = $dom.find('img').attr('src');
                    renderVideo( $dom , $dom.data('mp4') , $dom.data('webm') , poster , {pause_button: true} , function(){
                        $dom.find('.video-wrap').css('zIndex',1);
                    } );
                });

                if( !isMobile ){
                    // render initHoverMoveEffect
                    $('.inout-effect:not(.nav-pop-item)').each(function(){
                        initHoverMoveEffect( $(this) );
                    });
                }



                // init scroll bar
                $('.j-scroll-bar').each(function(){
                    var $dom = $(this);
                    // split content first
                    var lineHeight = parseInt( $dom.css('lineHeight') );
                    $dom.height( 7 * lineHeight );

                    // init ite select
                    LP.use(['jscrollpane' , 'mousewheel'] , function(){
                        $dom.jScrollPane({autoReinitialise:true});
                    });
                });


                // fix productstit h2
                $( '.productstit h2' ).each(function(){
                    $(this).css('marginTop' , 100 - $(this).height() / 2 );
                });

                $(".arrows[data-title]").hover(function () {
                    $(this).html( '<span class="text">' + $(this).data('title').replace('「SHANG XIA」' , '') + '</span>' );
                }, function () {
                    $(this).text('');
                });

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
        // if( !$loading.length )
        //     $loading = $('<div class="loading-wrap"><div class="loading loading_small"></div></div>')
        //         .appendTo(document.body);

        var $loadingInner = $loading.find('.loading');
        // var positions = [-44,-142,-240,-338,-436,-534];
        // var interval = null;

        // var colors = {
        //     'black': 'rgba(0,0,0,.85)'
        // }
        var MAX_STEPS = 59;
        // var IMG_WIDTH = 57.9;
        // var IMG_HEIGHT = 56.2;
        var IMG_WIDTH = 103;
        var IMG_HEIGHT = 100;
        var current = 0;
        var target = 10;
        var interval;
        var stepCallBack = null;

        var isHomePage = false;
        var isToStart = false;
        return {
            process: function( index , total , cb ){
                stepCallBack = cb;
                target = Math.max( target , Math.round( index / total * MAX_STEPS ) );
            },
            start: function( ){
                current = 0;
                clearInterval( interval );
                var page = $('.head').data('page');
                interval = setInterval( function(){
                    if( current >= target ) return;
                    current++;


                    stepCallBack && stepCallBack( current / MAX_STEPS );

                    if( page == 'home-page' && current < 10 ){
                        $loadingInner.css('top' , 30 + current * 4 + '%' );
                    }

                    var x = ( ( current % 10 - 10 ) % 10 ) *  IMG_WIDTH;
                    var y = -~~( ( current - 1 ) / 10 ) * IMG_HEIGHT;
                    $loadingInner
                        .css('background-position' , x + 'px ' + y + 'px'  );
                } , 50 );
            },
            show: function( isNormal ){
                $(document.body).css('overflow' , 'hidden');

                if( !$loading.is(':visible') ){
                    $loading
                        .removeAttr('style')
                        .fadeIn();    
                }

                $loadingInner
                    .removeAttr('style');

                isHomePage = !isNormal;
                if( isNormal ){
                    $loadingInner.appendTo( $('.logo a').css('background' , 'none') )
                        .css('top' , 23 );
                    $loading.css('zIndex' , 90);
                    // fade out other navs
                    // $('.nav li,.hd_oter').each(function( i ){
                    //     $(this).delay(i * 100)
                    //         .animate({
                    //             marginTop: 30,
                    //             opacity: 0
                    //         } , 200);
                    // });
                }

            },
            hide: function( cb ){
                var timer = setInterval(function(){
                    if( current == MAX_STEPS ){
                        clearInterval( timer );
                        clearInterval( interval );
                        $(document.body).css('overflow' , 'auto');

                        if( !isHomePage ){
                            $loadingInner.appendTo( $loading.fadeOut() );
                            $('.logo a').removeAttr('style');
                        }
                        cb && cb();
                    }
                } , 200);
            }
        }
    })();


    function firstWindowLoaded(){
        if( isMobile ){
            $('.nav1 li').click(function(){
                var type = $(this).data('type');
                var $tar = $('.nav-pop-' + type).show().css('left' , '100%');

                $('.nav,.hd_language').animate({
                    left: '-100%'
                });

                $tar.animate({
                    left: 0
                });
            });
            return;
        }
        // =====================================================================================
        var navPopHoverTimer = null;
        var navPopHoverShowTimer = null;
        // init nav hover effect
        $('.nav1 li[data-type]').hover(function(){
            if( window.LOADING ) return;
            var $li = $(this);
            clearTimeout( navPopHoverShowTimer );
            navPopHoverShowTimer = setTimeout(function(){
                var text = $li.data('type');
                var $inner = $li.closest('.head-inner');
                $inner.attr('class' , 'head-inner cs-clear active-' + text );
                $li.addClass('active');

                $('.nav-pop-' + text ).stop(true).show()
                    .css('zIndex',99)
                    .animate({
                        top: $('.head-fixed').height()
                    } , 500 , '' , function(){
                        $(this).css('zIndex' , 101);
                    });

                // fix nav-pop-wrap height
                if( text == 'collections' ){
                    // reset the first item
                    $('.nav-pop-nav a').removeClass('hover').eq(0).addClass('hover');

                    $('.nav-pop-wrap-inner').css('marginLeft' , 0).each(function(){
                        var len = $(this).children().length ;
                        $(this).css('width' , len / 4 / 0.84 * 100 + '%' );
                        $(this).children().css({
                            width: 1 / len * 0.238 / 0.25 * 100 + '%' ,
                            margin: '0 ' + (1 / len * 0.006 / 0.25 * 100)+ '%' 
                        })
                        .eq(0)
                        .css('margin-left' , 0 );
                    });

                    $('.nav-pop-wraps').height( $('.nav-pop-wrap img').height() ).scrollTop(0);
                }
            } , 150);
        } , function(){
            var $li = $(this);
            clearTimeout( navPopHoverShowTimer );
            navPopHoverTimer = setTimeout(function(){
                $li.removeClass('active');
                var text = $li.data('type');
                var $inner = $('.head-inner').attr('class' , 'head-inner cs-clear');

                $('.nav-pop-' + text ).stop(true)
                    .css('zIndex' , 98)
                    .animate({
                        top: -250
                    } , 500);
            } , 100);
        });

        $('.nav-pop').hover(function(){
            clearTimeout( navPopHoverTimer );

        } , function(){
            clearTimeout( navPopHoverShowTimer );
            $('.nav1 li.active').trigger('mouseout');
        });



        // render initHoverMoveEffect
        $('.inout-effect.nav-pop-item').each(function(){
            initHoverMoveEffect( $(this) );
        });

        // nav-pop-item inout-effect
        $('.nav-pop-item.inout-effect').hover(function(){

            if($(this).data("order")=="Reverse"){
                $(this).find('span:not(.inout-bg)').stop(true , true ).fadeIn(700);
            }else{
                $(this).find('span:not(.inout-bg)').stop(true , true).fadeOut(700);
            }
        } , function(){
            if($(this).data("order")=="Reverse"){
                $(this).find('span:not(.inout-bg)').stop(true , true).fadeOut(700);
            }else{
                $(this).find('span:not(.inout-bg)').stop(true , true ).fadeIn(700);
            }
        });
        

        (function(){
            var isAtLeft = false;
            var isAtRight = false;
            var brandsNum = 0;
            var scrollNum = 0;

            var $wrap = null;
            
            $('.nav-pop-collections .nav-pop-wrap').mousemove(function( ev ){
                $wrap = $(this);
                var off = $wrap.offset();
                var width = $wrap.width();
                scrollNum = $wrap.scrollLeft();
                if( ev.pageX - off.left < width / 4 ){
                    if( !isAtLeft ){
                        isAtLeft = true;
                    }
                    brandsNum = ( 1 - ( ev.pageX - off.left ) * 4 / width ) * 16;
                } else if( width + off.left - ev.pageX < width / 4 ){
                    brandsNum = ( 1 - ( width + off.left - ev.pageX ) * 4 / width ) * 16; 
                    if( !isAtRight ){
                        isAtRight = true;
                    }
                } else {
                    isAtLeft = false;
                    isAtRight = false;
                }
            })
            .mouseout(function(){
                isAtLeft = false;
                isAtRight = false;
            });


            setInterval(function(){
                // fix brands_con
                if( isAtLeft ){
                    scrollNum -= brandsNum;
                    $wrap.scrollLeft( scrollNum );
                } else if ( isAtRight ){
                    scrollNum += brandsNum;
                    $wrap.scrollLeft( scrollNum );
                }
            } , 1000 / 60);
        })();
        // var isRunning = false;
        // // init nav pop mouse move event
        // $('.nav-pop-collections .nav-pop-wrap-inner').mousemove(function( ev ){
        //     var $inner = $(this);
        //     var len = $inner.children().length;
        //     if( len < 4 ) return;
        //     var winWidth = $(window).width();
        //     var off = $inner.parent().offset();
        //     if( ev.pageX / winWidth < 0.7 && ( ev.pageX - off.left ) / winWidth > 0.3 ){
        //         isRunning = false;
        //         $inner.stop( true );
        //     } else {
        //         if( isRunning ) return;


        //         var marginleft = Math.abs( parseInt( $inner.css('marginLeft') ) / $inner.parent().width() );
        //         console.log( Math.abs( ( ( len / 4 / 0.84 - 1 ) - marginleft ) ) );
        //         if( ev.pageX / winWidth > 0.7 ){
        //             isRunning = true;
        //             $inner.stop( true )
        //                 .animate({
        //                     marginLeft: - ( len / 4 / 0.84 - 1 ) * 100 + '%'
        //                 } , 500 * Math.abs( ( ( len / 4 / 0.84 - 1 ) - marginleft ) ) );
        //         } else {
        //             isRunning = true;
        //             $inner.stop( true )
        //                 .animate({
        //                     marginLeft: 0
        //                 } , 500 * (marginleft) / 100 );
        //         }
        //     }
        // })
        // .mouseout(function(){
        //     $('.nav-pop-wrap-inner').stop( true );
        //     isRunning = false;
        // });
    }

    function initSlider( $wrap , config ){

        var $slidebox = $wrap.find('.slidebox');
        var $slidetabs = $wrap.find('.slidetab li');
        if( $slidetabs.length == 1 && !$slidetabs.html() ){
            $slidetabs.hide();
        }
        var currentIndex = 0;
        var length = $slidebox.children().length;
        var isAbs = $wrap.data('slide') == 'absolute';
        if( isAbs ){
            // $slidebox.children().find('img')
            //     .eq(0)
            //     .load(function(){
            //         $wrap.height( this.height );
            //     });
            // $(window).resize(function(){
            //     $wrap.height( 
            //         $slidebox.children().find('img')
            //             .eq(0).height() );
            // });

            $slidebox.children().css({
                position: 'absolute',
                width: '100%',
                left:0,
                top: 0,
                zIndex: 0
            })
            .eq(0)
            .css('zIndex' , 1);


            $slidetabs.click(function(){
                clearInterval( $wrap.data('interval') );
                $wrap.data('interval' , setInterval(function(){
                    if( new Date() - start_time < 4000 ) return;
                    var $next = $slidetabs.filter('.on').next();
                    if( $next.length ){
                        $next.trigger('click');
                    } else {
                        $slidetabs.eq(0).trigger('click');
                    }
                } , 5000) );

                if( $(this).hasClass('on') ) return false;
                if( $slidebox.find('.video-wrap').length ){
                    disposeVideo();
                    // change btn text
                    var $btn = $('a[data-a="homepage-watch-video"]');
                    $btn.find('i').html( $btn.data('play-text') + '<br/><br/>' + $btn.data('play-text') );
                }

                var lastIndex = $slidetabs.filter('.on').index();
                $(this).addClass('on')
                    .siblings()
                    .removeClass('on');

                var index = $(this).index();

                $slidebox.children().eq( index )
                    .css({'zIndex': 2 , left: '100%'})
                    .stop(true)
                    .animate({
                        left: 0
                    } , 400 , function(){
                        var $dom = $(this);

                        if( isMobile ){
                            renderVideo( $dom , $dom.data('mp4') , $dom.data('webm') , $dom.find('img').attr('src') , {pause_button: true} , function(){
                                $dom.find('.video-wrap').css('zIndex',1);
                            } );
                        }


                        $(this).css('zIndex' , 1)
                            .siblings()
                            .css('zIndex' , 0);

                        $wrap.find('.slidetip2-tit').html( $dom.data('tit') )
                            .end()
                            .find('.slidetip2-index')
                            .html( ( index + 1 ) + '/' + $wrap.find(".slidebox").children().length );
                    } );

                return false;
            });
        } else {
            $slidebox.css( 'width' , length * 100 + '%' )
                .children()
                .css('width' , 1 / length * 100 + '%' );

            $slidetabs.click(function(){
                $(this).addClass('on')
                    .siblings()
                    .removeClass('on');


                start_time = +new Date();

                var index = $(this).index();
                $slidebox.stop(true).animate({
                    marginLeft: - index * 100 + '%'
                } , 400 );

                return false;
            });
        }
        var start_time = 0;

        if( $wrap.data('auto') !== false ){
            $wrap.data('interval' , setInterval(function(){
                if( new Date() - start_time < 4000 ) return;
                var $next = $slidetabs.filter('.on').next();
                if( $next.length ){
                    $next.trigger('click');
                } else {
                    $slidetabs.eq(0).trigger('click');
                }
            } , 5000) );
        }
    }


    function initHoriSlider( $doms , cb ){
        $doms.each(function(){
            var $dom = $(this);

            // split content first
            if( $dom.data('split') ){
                var $li = $dom.find('.slide-con-inner li');
                var height = $li.height();
                var lineHeight = parseInt( $li.css('lineHeight') );
                var len = Math.ceil( height / ( 5 * lineHeight ) );
                $li.html( $('<div></div>').html( $li.html() ).height( 5 * lineHeight ).css('overflow' , 'hidden') );
                for( var i = 1 ; i < len ; i++ ){
                    $li.clone()
                        .appendTo( $li.parent() )
                        .children('div')
                        .append( i == len - 1 ? '<br/><br/><br/><br/><br/>' : '' )
                        .scrollTop( i * 5 * lineHeight );
                }
            }


            var wrapWidth = $dom.width();
            var num = $(this).data('num') || 3;
            var $items = $dom.find('.slide-con-inner').children();

            // 计算每一个item的宽度 
            var $imgs = $items.find('img');
            var totalItems = 0;
            if( $imgs.length && !isMobile ){
                $imgs.each(function(){
                    totalItems += Math.round( $(this).data('width') / $(this).data('height') ) || 1;
                });
            } else {
                totalItems = $items.length;
            }
            

            var $inner = $dom.find('.slide-con-inner').width( totalItems / num * 100 + '%' );
            var marginRight = num == 1 ? 0 : 0.8 /( totalItems / num  );//parseInt( $items.css('margin-right') );
            var halfMR = marginRight / 2 + '%';
            var counted = 0;
            var prev = 0;
            $items
                .css('marginRight' , marginRight + '%' )
                .each(function(){
                var $this = $(this);
                var $img = $this.find('img');
                var indent = Math.round( $img.data('width') / $img.data('height') ) || 1;
                counted += indent;
                if( isMobile ){
                    $this.css({
                        marginLeft: 0, 
                        marginRight: 0,
                        overflow: 'hidden',
                        width: 1/totalItems * 100 + '%'
                    });

                    $img.css({
                        marginLeft: - ( indent - 1 ) / 2 * 100 + '%',
                        width: indent * 100 + '%'
                    });
                } else {
                    if( counted % 3 == 0 ){
                        if( indent == 1 && prev == 1 ){ // 111
                            // 第一个margin-left: 0.4%
                            // 最后一个margin-right: 0.4 %
                            $this
                            .css('marginRight' , halfMR)
                            .prev()
                            .prev()
                            .css({
                                marginLeft: halfMR ,
                                marginRight: marginRight + '%'
                            });
                        } else if( indent == 3 ) { // 3
                            $this.css({
                                marginLeft: halfMR,
                                marginRight: halfMR
                            });
                        } else { // 21 || 12
                            $this.css('marginRight' , halfMR)
                                .prev()
                                .css({
                                    'marginLeft': halfMR,
                                    'marginRight': marginRight + '%'
                                });
                        }
                    } else if( ( counted - indent ) % 3 == 0 ){
                        $this.css({
                            marginLeft: halfMR ,
                            marginRight: halfMR
                        });
                    }

                    $this.width( indent /  totalItems * 100 - marginRight + '%' );
                }
                
                prev = indent;
            });

            var total = $items.length;
            $dom.find('.collarrowsprev')
                .fadeOut()
                .click(function(){
                    var ml = parseInt( $inner.css('marginLeft') ) || 0;
                    if( ml == 0 ) return false;

                    var mleft = -Math.round( Math.abs( ml ) / $inner.parent().width() - 1 );
                    $inner.animate({
                        marginLeft: mleft * 100 + '%'
                    } , 500);

                    cb && cb( -mleft );

                    if( mleft == 0 ){
                        // hide current btn
                        $(this).fadeOut();
                    }

                    // show next btn
                    $dom.find('.collarrowsnext').fadeIn();

                    $(window).trigger('scroll');
            })
            .end()
            .find('.collarrowsnext')
            [ totalItems > num ? 'show' : 'hide' ]()
            .click(function(){
                var ml = parseInt( $inner.css('marginLeft') ) || 0;
                var outerWidth = $inner.parent().width();
                var innerWidth = $inner.width();
                if( Math.abs( ml ) >= innerWidth - outerWidth - 100 ) return false;
                var mleft = -Math.round( Math.abs( ml ) / outerWidth + 1 );
                $inner.animate({
                    marginLeft: mleft * 100 + '%'
                } , 500 );

                cb && cb( -mleft );

                if( Math.abs( parseInt( $inner.css('marginLeft') ) ) + outerWidth >= innerWidth - outerWidth - 100 ){
                    // hide current btn
                    $(this).fadeOut();
                }

                // show pre btn
                $dom.find('.collarrowsprev').fadeIn();

                $(window).trigger('scroll');
            });

            cb && cb( 0 );
        });
    }


    function disposeVideo(){
        $(document.body).find('.video-wrap').parent()
            .each(function(){
                var video = $(this).data('video-object');
                try{video && video.dispose();}catch(e){}
                $(this).removeData('video-object').find('.video-wrap').remove();
            });
    }

    function loadImages( $img , cb , step ){
        var index = 0 ;
        var length = $img.length;
        if( length == 0 ){
            step && step( 1 , 1 );
            cb && cb();
        }
        $img.each(function(){
            $('<img/>').load(function(){
                index ++;
                step && step( index , length );
                if( index == length ){
                    cb && cb();
                }
            })
            .error(function(){
                index ++;
                step && step( index , length );
                if( index == length ){
                    cb && cb();
                }
            })
            .attr('src' , this.getAttribute('src'));
        });
    }


    function fixImageToWrap( $wrap , $img ){
        if( !$img.width() ){
            $img.load(function(){
                fixImageToWrap( $wrap , $img );
            });
            return ;
        }
        var ratio = $img.height() / $img.width();
        var w = $wrap.width()  ;
        var h = $wrap.height() ;
        var vh = 0 ;
        var vw = 0 ;
        var exp = 0;
        if( h / w > ratio ){
            vh = h + exp;
            vw = vh / ratio;
        } else {
            vw = w + exp;
            vh = vw * ratio;
        }

        $img.css({
            width: vw,
            height: vh,
            marginTop: ( h - vh ) / 2,
            marginLeft: ( w - vw ) / 2
        });
    }

    function renderVideo ( $wrap , mp4 , webm , poster , config , cb ){
        var id = 'video-js-' + ( $.guid++ );
        $wrap.append( LP.format( '<div class="video-wrap" ><video id="#[id]" style="width: 100%;height: 100%;" class="video-js vjs-default-skin"\
            preload="auto"\
              poster="#[poster]">\
             <source src="#[mp4]" type="video/mp4" />\
             <source src="#[webm]" type="video/webm" />\
        </video></div>' , {id: id  , mp4: mp4 , webm: webm , poster: poster}));

        config = $.extend( { "controls": false, "muted": false, "autoplay": false, "preload": "auto","loop": true, "children": {"loadingSpinner": false} } , config || {} );
        var ratio = config.ratio || 9/16;

        var getSize = function(){
            var w = $wrap.width()  ;
            var h = $wrap.height() ;
            var vh = 0 ;
            var vw = 0 ;
            var exp = 0;
            if( h / w > ratio ){
                vh = h + exp;
                vw = vh / ratio;
            } else {
                vw = w + exp;
                vh = vw * ratio;
            }

            return {
                h: h,
                w: w,
                width: vw,
                height: vh
            }
        }
        var size = getSize();

        config.width = size.width;
        config.height = size.height;
        if( window.navigator.userAgent.indexOf('Firefox') >= 0 ){
            config['techOrder'] = ['flash'];
        }
        
        LP.use('video-js' , function(){
            var is_playing = false;
            videojs.options.flash.swf = "/js/video-js/video-js.swf";
            var myVideo = videojs( id , config , function(){
                var v = this;
                if( config.resize !== false ){
                    var resizeFn = function(){
                        var size = getSize();
                        try{v.dimensions( size.width , size.height );}catch(e){}

                        // var w = $wrap.width()  ;
                        // var h = $wrap.height() ;
                        // var vh = 0 ;
                        // var vw = 0 ;
                        // var exp = 0;
                        // if( h / w > ratio ){
                        //     vh = h + exp;
                        //     vw = vh / ratio;
                        // } else {
                        //     vw = w + exp;
                        //     vh = vw * ratio;
                        // }

                        // try{v.dimensions( vw , vh );}catch(e){}
                        $('#' + v.Q).css({
                            "margin-top": ( size.h -  size.height ) / 2,
                            "margin-left": ( size.w - size.width ) / 2
                        });
                        return false;
                    }
                    $(window).bind( 'resize.video-' + id , resizeFn )
                        .trigger('resize.video-' + id);

                    $wrap.bind('resize.video-' + id , resizeFn);
                }
                setTimeout(function(){
                    $wrap.find('.video-wrap').show();
                    if( config.autoplay ){
                        try{myVideo.play();}catch(e){}
                    } else if( config.pause_button ){
                        $wrap.find('.vjs-big-play-button').fadeIn();
                    }
                } , 20);

                // if need to add pause button
                if( config.pause_button ){
                    $('<div></div>').insertBefore($wrap.find('.vjs-poster').fadeIn() )
                        .css({
                            position: 'absolute',
                            top: 0,
                            left:0,
                            width: '100%',
                            height: '100%'
                        });
                    if( !config.controls ){
                        $wrap.off( isMobile ? 'touchend' : 'click.video-operation').on( isMobile ? 'touchend' : 'click.video-operation' , function(){
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

                        // if iphone , hide the poster
                        if( isIphone ){
                            $wrap.find('.vjs-poster').hide();
                        }
                    });

                    v.on('pause' , function(){
                        is_playing = false;
                        $wrap.find('.vjs-big-pause-button').hide();
                        $wrap.find('.vjs-big-play-button').fadeIn();

                        // if iphone , show the poster
                        if( isIphone ){
                            $wrap.find('.vjs-poster').show();
                        }
                    });
                }

                v.on('play' , function(){
                    var interval = setInterval(function(){
                        if( v.v.currentTime > 0.1 ){
                            clearInterval( interval );
                            $wrap.find('.video-wrap').css('zIndex' , 1);
                        }
                    } , 100);

                });
                //if( config.hasPoster ){
                    // $('<img/>').load(function(){
                    //     var width = this.width;
                    //     var height = this.height;
                    //     fixImageToWrap( $wrap , $wrap.find('.vjs-poster').show().css({
                    //         width: width,
                    //         height: height
                    //      }) );
                    // }).attr('src' , poster);
                     
                //}


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
        var isInNav = !!$bg.closest('.nav-pop-inner').length;
        $dom.css('position','relative');

        if($dom.data("order")=="Reverse"){
            $bg.css( {left:0 , top: 0, opacity:0} );
            $dom.find('.nav-text').hide();
        }

        $dom.hover(function( ev )
        {
            //var width = $dom.width();
            //var height = $dom.height();
            //
            //var off = $dom.offset();
            //var topOff = ev.pageY - off.top;
            //var leftOff = ev.pageX - off.left;
            //var bottomOff = height + off.top - ev.pageY;
            //var rightOff = width + off.left - ev.pageX;
            //
            //var min = Math.min( topOff , leftOff , bottomOff , rightOff );
            //var ori = null;
            //var tar = null;
            //if( !isInNav ){
            //    if( min == topOff ){ // from top
            //        ori = { left: 0,top: '-100%'};
            //        tar = { top: 0 };
            //    } else if( min == leftOff ){
            //        ori = { left: '-100%',top: 0};
            //        tar = { left: 0 };
            //    } else if( min == bottomOff ){
            //        ori = { left: 0,top: '100%'};
            //        tar = { top: 0 };
            //    } else {
            //        ori = { left: '100%',top: 0};
            //        tar = { left: 0 };
            //    }
            //} else {
            //    ori = {left:0 , top: 0};
            //
            //    if( min == topOff ){ // from top
            //        tar = { top: '100%' };
            //    } else if( min == leftOff ){
            //        tar = { left: '100%' };
            //    } else if( min == bottomOff ){
            //        tar = { top: '-100%' };
            //    } else {
            //        tar = { left: '-100%' };
            //    }
            //}

            var ori = {left:0 , top: 0, opacity:0.5};
            var tar = { opacity:0};
            if($dom.data("order")=="Reverse"){
                ori = {left:0 , top: 0, opacity:0};
                tar = { opacity:0.5};
            }
            $bg.css( ori ).stop( true )
                .animate( tar , 500 );

            hoverin && hoverin();
        } , function( ev )
        {
            //var width = $dom.width();
            //var height = $dom.height();
            //
            //var off = $dom.offset();
            //var topOff = ev.pageY - off.top;
            //var leftOff = ev.pageX - off.left;
            //var bottomOff = height + off.top - ev.pageY;
            //var rightOff = width + off.left - ev.pageX;
            //
            //var min = Math.min( topOff , leftOff , bottomOff , rightOff );
            //var ori = null;
            //var tar = null;
            //if( !isInNav ){
            //    if( min == topOff ){ // from top
            //        ori = { left: 0,top: '-100%'};
            //        tar = { top: 0 };
            //    } else if( min == leftOff ){
            //        ori = { left: '-100%',top: 0};
            //        tar = { left: 0 };
            //    } else if( min == bottomOff ){
            //        ori = { left: 0,top: '100%'};
            //        tar = { top: 0 };
            //    } else {
            //        ori = { left: '100%',top: 0};
            //        tar = { left: 0 };
            //    }
            //    $bg.stop( true )
            //        .animate( tar , 500 );
            //} else {
            //    tar = { top: 0 , left: 0};
            //    if( min == topOff ){ // from top
            //        ori = { left: 0,top: '100%'};
            //    } else if( min == leftOff ){
            //        ori = { left: '100%',top: 0};
            //    } else if( min == bottomOff ){
            //        ori = { left: 0,top: '-100%'};
            //    } else {
            //        ori = { left: '-100%',top: 0};
            //    }

                var ori = {left:0 , top: 0, opacity:0};
                var tar = { opacity:0.5};
                if($dom.data("order")=="Reverse"){
                    ori = {left:0 , top: 0, opacity:0.5};
                    tar = { opacity:0};
                }
                $bg.stop( true )
                    .css(ori)
                    .animate( tar , 500 );
            //}


            hoverout && hoverout();
        });
    }

    var mapHelper = (function(){
        return {
            render: function( $dom ){
                var point = $dom.data('map').split(',');
                if( $dom.data('map-type') == 'google' ){ // use baidu
                    this.renderGoogle( $dom , point );
                } else {
                    this.renderBaidu( $dom , point );
                }
                $dom.removeAttr('data-map');
            },
            renderBaidu: function( $dom , point ){
                point[0] = point[0] || 0;
                point[1] = point[1] || 0;
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

                        var myIcon = new BMap.Icon("/images/marker.png", new BMap.Size(34,40));
                        var marker2 = new BMap.Marker(point,{icon:myIcon});  // 创建标注
                        oMap.addOverlay(marker2);              // 将标注添加到地图中
                    }
                } , 100 );
            },
            renderGoogle: function( $dom , point ){
                if( !window.google ) return;
                point[0] = point[0] || 0;
                point[1] = point[1] || 0;


                var map = new google.maps.Map($dom[0],{
                    center: new google.maps.LatLng(point[0],point[1]),
                    zoom:17,
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
                    icon: "/images/marker.png"
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
        var tpl ='<div class="cccon"><div class="popshade"></div>\
            <div class="pop">\
                <a href="#" class="popclose" data-a="popclose"></a>\
                <div class="popcon transition">#[con]</div>\
            </div></div>';
        return {
            show: function( con , data ){
                data = data || {};
                var html = LP.format( tpl , { con: con } );
                var $pop = $( html )
                    .appendTo( document.body )
                    .hide()
                    .fadeIn( function(){
                        $(this).find('.popcon')
                            .addClass( 'popcon-show' );
                    } );
                if( data.press ){
                    $pop.find('.popcon')
                        .css({
                            width: '100%',
                            padding: 0,
                            textAlign: 'center'
                        });
                    
                    var $pressVideo = $pop.find('.press-video');
                    if( $pressVideo.length ){
                        $pop.find('.popcon').css('height' , '100%');
                        setTimeout(function(){
                            renderVideo( $pressVideo , $pressVideo.data('video') , $pressVideo.data('video') , '' , {autoplay: true , pause_button: true } );
                        } , 700);
                    } else {
                        $pop.addClass('pop-press');
                    }
                } else if( data.qr ){
                    $pop.find('.popcon').addClass('qr_pop');
                }


                $('html,body').css('overflow' , 'hidden');
            },
            hide: function(){
                var $pop = $('.pop').fadeOut( function(){
                    $(this).remove();
                } );
                $('.popshade').fadeOut( function(){
                    $(this).remove();
                } );

                $('html,body').css('overflow' , 'auto');
            }
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
    $(document).click(function( ev ){
        if ( $(ev.target).closest('.searchform').length || $(ev.target).hasClass('hd_search') ){
            return;
        }
        if( $('.searchform').is(':visible') )
            $('.searchform').fadeOut();

    });

    firstWindowLoaded();

    var headHeight = $('.head').height();

    $(window).resize(function(){
        var winWidth = $(window).width();
        var winHeight = $(window).height();

        // fix home slider
        if( !isMobile ){
            $('#home-slider').height( isTablet ? 950 / 1600 * winWidth : winHeight - headHeight )
                .find('.slideitem')
                .each(function(){
                    // fix image size
                    fixImageToWrap( $(this) , $(this).find('img') );
                });

        }
        

        // fix slidetab marginleft
        $('.slidetab').each(function(){
            $(this).css('marginLeft' , - $(this).width() / 2);
        });

        // fix height
        // $('.knowhowintro').each(function(){
        //     var h = $(this).parent('.knowhowitem').children('.knowhowpic').height()
        //     $(this).css('padding-top' , (h - $(this).height())/2)
        // })

        //lang check
        //if ( $("#someID").length > 0 ) {
        //    $("#someID").text("hi");
        //}

        if( !isMobile ){
            $('.knowhowintro').each(function(){
                var h = $(this).parent('.knowhowitem').children('.knowhowpic').height()
                $(this).css('height' , h);
                var $wrap = $(this).children('.cwrap');
                $wrap.css('padding-top' , (h - $wrap.height())/2);
            });

            $('.proinfortxt').each(function(){
                var h = $(this).siblings('.proinforpic').height();
                $(this).height( h );
                var $proinfortxt=$(this);
                var txtLeightPif=h;
                var $proinforinner=$proinfortxt.find('.proinfortxt-inner');
                $proinforinner.find('p').unwrap('<div class=\'proinforp\'></div>').wrapAll('<div class=\'proinforp\'></div>');
                var $proinforp=$proinforinner.find('.proinforp');
                var lineheight=parseInt($proinfortxt.find('p').css('line-height'));
                var ua = navigator.userAgent.toLowerCase();
                if(ua.indexOf("firefox")>0) lineheight=parseFloat($proinfortxt.find('p').css('line-height'));
                    if($proinfortxt.find('h2').height()){
                        txtLeightPif=txtLeightPif-$proinfortxt.find('h2').height() - 20;
                    }
                    if($proinfortxt.find('h3').height()){
                        txtLeightPif=txtLeightPif-$proinfortxt.find('h3').height() - 20;
                    }
                    txtLeightPif-= 290;
                    txtLeightPif= parseInt(txtLeightPif/parseFloat(lineheight))*parseFloat(lineheight);
                    if (txtLeightPif < parseFloat(lineheight)){
                        txtLeightPif = parseFloat(lineheight);
                    }


                $(this).find('.proinforp')
                    .css({
                        marginBottom: 50,
                        overflow: 'hidden',
                        height: txtLeightPif
                    });
                //var h = $(this).siblings('.proinforpic').height();
                //$(this).height( h );
                //$(this).find('.proinfortxt-inner')
                //    .css({
                //        marginBottom: 30,
                //        overflow: 'hidden',
                //        height: h - 290
                //    });
            });

            var resizepicinfortxt = false;
            $('.picinfortxt').each(function(){
                var $this = $(this);
                var $picWrap = $(this).next('.picinforpic');
                loadImages( $picWrap.find('img') , function(){
                    var h = $picWrap.height();
                    $this.height( h - 50 ).css({
                        overflow: 'hidden',
                        paddingBottom: 50
                    })
                    .find('.picinfortxt-inner').height( h - 100 )
                        //.css('overflow' , 'hidden')
                    ;


                    //var $inner = $('.news-picinfortxt .picinfortxt-inner');
                    //if( $inner.length && !resizepicinfortxt ){
                    //    resizepicinfortxt = true;
                    //    var ttop = $inner.offset().top;
                    //    var theight = $inner.height();
                    //
                    //    var bodyTop = $inner.find('.body').offset().top;
                    //    var lineHeight = parseInt( $inner.find('.body').css('lineHeight') );
                    //    var ua = navigator.userAgent.toLowerCase();
                    //    if(ua.indexOf("firefox")>0) lineHeight=parseFloat( $inner.find('p').css('lineHeight'));
                    //    $inner.find('.body').css({
                    //        'height': ~~( ( ttop + theight - bodyTop ) / lineHeight ) * lineHeight,
                    //        overflow: 'hidden'
                    //    });
                    //}

                    var h = $picWrap.height();
                    var $proinfortxt=$this;
                    var txtLeightPif=h-100;
                    var $proinforinner=$proinfortxt.find('.picinfortxt-inner');
                    $proinforinner.find('p').unwrap('<div class=\'picinfop\'></div>').wrapAll('<div class=\'picinfop\'></div>');
                    var $proinforp=$proinforinner.find('.picinfop');
                    var lineheight=parseInt($proinfortxt.find('p').css('line-height'));
                    var ua = navigator.userAgent.toLowerCase();
                    if(ua.indexOf("firefox")>0) lineheight=parseFloat($proinfortxt.find('p').css('line-height'));
                    if($proinfortxt.find('h2').height()){
                        console.log(txtLeightPif);
                        txtLeightPif=txtLeightPif-$proinfortxt.find('h2').height() - 20;
                    }
                    if($proinfortxt.find('h3').height()){
                        console.log(txtLeightPif);
                        txtLeightPif=txtLeightPif-$proinfortxt.find('h3').height() - 20;
                    }
                    console.log(txtLeightPif);
                    //txtLeightPif-= 290;
                    txtLeightPif= parseInt(txtLeightPif/parseFloat(lineheight))*parseFloat(lineheight);
                    if (txtLeightPif < parseFloat(lineheight)){
                        txtLeightPif = parseFloat(lineheight);
                    }


                    console.log(txtLeightPif);
                    $this.find('.picinfop')
                        .css({
                            marginBottom: 50,
                            overflow: 'hidden',
                            height: txtLeightPif
                        });
                } );
                // var h = $(this).next('.picinforpic').height();
                // $(this).height( h - 50 ).css({
                //     overflow: 'hidden',
                //     paddingBottom: 50
                // });
                // $(this).find('.picinfortxt-inner').height( h - 100 )
                //     .css('overflow' , 'hidden');
            });
        }

        $('.aboutinfortxt').each(function(){
            var h = $(this).siblings('.proinforpic').height();
            $(this).height( h - 80 )
                .find('.picinfortxt-inner')
                .css({
                    height: h - 280,
                    marginBottom: 40
                });
        });


        $('.storechoose2').css('marginTop' , ( 390 - $('.storechoose2').height() ) / 2 );


        // fix resize:
        $('[data-resize]').each(function(){
            var val = $(this).data('resize');
            val = val.split(':');
            var height = $(this).width() / val[0] * val[1];
            $(this).css({
                height: height
            });
            fixImageToWrap( $(this) , $(this).find('img') );
        });


        // data-resize for press-page
        $('.press-list[data-year]').each(function(){
            $(this).children('a').filter(function(i){
                return i > 0;
            })
            .height( $(this).children('a').eq(0).height() );
        });


        // fix news press  crumb position
        $('.newscrumbs p').css('padding-left' , ( $('.nav1 li').width() - $('.nav1 li a').width() ) / 2 );

        // fix collection nav
        $('.nav-pop-wraps').height( $('.nav-pop-wraps img').height() );

        // fix header space
        // var totalWidth = 0;
        // var headerItemWidths = [];
        // var $headerItems = $('.logo a,.nav li a').each(function(){
        //     var width = $(this).width();
        //     $(this).data('width')
        //     totalWidth += width;
        // });

        // var allWidth = Math.min( $('.hd_oter').offset().left - $('.head-inner').offset().left , $('.head-inner').width() );
        // $headerItems.css('padding' , '0 ' + ( allWidth - totalWidth ) / $headerItems.length / 2 + 'px' );
        // alert(allWidth);


        if( isMobile ){
            $('.news-picinfortxt , .picinfortxt').each(function(){
                $(this).insertAfter( $(this).next() );
            });
        }

    })
    .keyup(function( ev ){
        switch( ev.which ){
            case 27:
                var dom = $('.pop .popclose').get(0);
                dom && dom.click();
                break;
        }
    });

    

    // fix nav-pop-nav ============================================================================================
    var $navPopLinks = $('.nav-pop-nav a').hover( function(){
        // fix hover class
        $navPopLinks.removeClass('hover');
        $(this).addClass('hover');

        var index = $navPopLinks.index( this );
        $('.nav-pop-wraps').stop( true , true )
            .animate({
                scrollTop: index * $('.nav-pop-wraps').height() + index * 10
            } , 500 );
    });



    // change history
    LP.use('../plugin/history.js' , function(){
        History.replaceState( { prev: '' } , undefined , location.href  );

        var isHomePage = ( location.pathname == '' || location.pathname == '/' || location.pathname == '/index.php' );
        loadingMgr.show( !isHomePage );
        pageManager.init( );

        $(document).ajaxError(function(){
            loadingMgr.hide();
        });
        // Bind to StateChange Event
        History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate

            var State = History.getState(); // Note: We are using History.getState() instead of event.state
            var prev = State.data.prev;
            var type = State.data.type;
            var hash = State.data.hash;
            // if only change hash
            if( State.url.indexOf('##') >= 0 || State.data.needAjax === false ){
                return false;
            }
            // hid head pop
            $('.nav1 li.active').trigger('mouseout');

            // show loading
            var isHomePage = ( location.pathname == '' || location.pathname == '/' || location.pathname == '/index.php' );
            loadingMgr.show( !isHomePage );


            switch( type ){
                default: 
                    $.get( location.href , '' , function( html ){
                        var $newPage = $('<div>' + html + '</div>');
                        // set body class
                        var bodyClass = $newPage.find('body').attr('class');
                        var page = $newPage.find('.head').data('page');
                        $('.head').data('page' , page || '');
                        $(document.body).attr('class' , bodyClass || '');
                        // set title
                        document.title = $newPage.find('title').html();

                        html = $newPage.find('.wrap')
                            .find( isHomePage ? '.footer' : '.footer,.head' )
                            .remove()
                            .end()
                            .html();
                        $( '.wrap' ).children(isHomePage ? ':not(.footer)' : ':not(.footer,.head)').animate({
                            opacity: 0
                        } , 500);

                        setTimeout(function(){
                            $('#home-slider').remove();
                            $( '.wrap' ).children( isHomePage ? ':not(.footer)' : ':not(.footer,.head)' )
                                .remove();
                            $(html).insertBefore( $('.footer') );
                            $( '.wrap' )
                                .children( isHomePage? '' : ':not(.head)')
                                .fadeIn();
                            //pagetitarrbottom

                            var sttop = 0;
                            if( hash ){
                                location.hash = hash;
                                var $a = $('a[name="' + hash + '"]'); 
                                sttop = $a.length && $a.offset().top - headHeight;
                            }
                            $('html,body').animate({
                                scrollTop: sttop || 0
                            } , 300 );
                            pageManager.destroy( );
                            pageManager.init( );
                            
                        } , 500);
                    });
            }
        });
    });

    // popups
    $(function(){
        if( window.crtgiftId ){
            $('[data-cid="' + window.crtgiftId + '"] img').click();
        }
    });
    



    // page actions here
    // ============================================================================
    // LP.action('nav-pop' , function( data ){
        
    //     var text = data.type;
    //     var $inner = $(this).closest('.head-inner');
    //     $inner.attr('class' , 'head-inner cs-clear active-' + text );
        
    //     if( $('.nav-pop-' + text ).is(':visible') ){
    //         LP.triggerAction('nav-mask');
    //         return false;
    //     }
    //     $('.head .nav li').removeClass('active');
    //     $(this).closest('li').addClass('active');

    //     $('.nav-pop').fadeOut();
    //     $('.nav-pop-' + text ).stop(true , true).fadeIn();
    //     $('.nav-mask').fadeIn();
    //     return false;
    // });

    // LP.action('nav-mask' , function(){
    //     var $inner = $('.head-inner').attr('class' , 'head-inner cs-clear');
    //     $('.head-inner').find('li.active').removeClass('active');
    //     $('.nav-pop').fadeOut();
    //     $('.nav-mask').fadeOut();

    //     return false;
    // });

    //LP.action('nav-pop', function (data) {
    //    var scrollTop =($(document).scrollTop() + $(this).parents('.head').offset()['top'] + "px");
    //    console.log(scrollTop);
    //    $('html, body').animate({scrollTop: scrollTop}, 500);
    //});



    LP.action('nav-link' , function(){
        if( isMobile ) return;
        if( $('html').hasClass('no-history') ) return;
        disposeVideo();
        // load next page
        var lhref = location.href;
        var href = $(this).attr('href');

        if( href.indexOf('#') >= 0 ){
            var hrefs = href.split('#');
            if( lhref.indexOf( hrefs[0] ) >= 0 ){
                $('html,body').animate({
                    scrollTop: $('a[name="' + hrefs[1] + '"]').offset().top - 130
                } , 300 );
                return false;
            }
        } else if( lhref.match( new RegExp( href + '$' ) ) ){ // 如果是同一个页面
            History.Adapter.trigger( window , 'statechange');
            return false;
        }

        if( $('#home-slider').length ){
            $('html,body').animate({
                scrollTop: $('#home-slider').height()
            } , 400)
            .promise()
            .then(function(){
                $(this).scrollTop(0);
                $('#home-slider').remove();
                $('.head-fixed').removeAttr('style');

                pageManager.gotoPage( href );
            });
        } else {
            pageManager.gotoPage( href );
        }

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
        var playText = $dom.data('play-text');
        var pauseText = $dom.data('pause-text');
        var $tip = $dom.closest('.slidetip-wrap');
        
        var index = $tip.next().find('.on').index();
        var $li = $tip.prev().children().eq( index );
        var stopHtml = playText + '<br><br>' + playText;
        var playHtml = pauseText + '<br><br>' + pauseText;

        if( $li.find('.video-wrap').length ){
            var obj = $li.data('video-object');
            var isPaused = obj.paused();
            obj[ isPaused ? 'play' : 'pause' ]();

            $dom.find('i').html( isPaused ? playHtml : stopHtml );
        } else {
            disposeVideo();
            renderVideo( $li , $li.data('mp4') , $li.data('webm') , $li.find('img').attr('src') , {autoplay: true } );
            $dom.find('i').html( playHtml );
        }

        return false;
    });


    // LP.action('page-prev' , function(){
    //     if( $(this).data('url') ){
    //         pageManager.gotoPage( $(this).data('url') );
    //         return false;
    //     }
    //     var href = location.href;
    //     var $links = $('.sitelinkitem a');
    //     $links.each(function( i ){
    //         var h = $(this).attr('href');
    //         h = h.replace(/.\/|..\//g , '');
    //         if( href.indexOf( h ) >= 0 ){
    //             var $link = $links.eq( i - 1 );
    //             $link[0] && $link[0].click();
    //             return false;
    //         }
    //     });
    // });

    // LP.action('page-next' , function(){
    //     if( $(this).data('url') ){
    //         pageManager.gotoPage( $(this).data('url') );
    //         return false;
    //     }
    //     var href = location.href;
    //     var $links = $('.sitelinkitem a');
    //     $links.each(function( i ){
    //         var h = $(this).attr('href');
    //         h = h.replace(/.\/|..\//g , '');
    //         if( href.indexOf( h ) >= 0 ){
    //             var $link = $links.eq( i + 1 );
    //             $link[0] && $link[0].click();
    //             return false;
    //         }
    //     });
    // });


    LP.action('show-pop' , function( data ){
        var $this = $(this);
        var con = '';
        if( $this.find('textarea').length ){
            con = $this.find('textarea').val();
        } else if( $(this).siblings('textarea').length ){
            con = $(this).siblings('textarea').val();
        } else {
            con = $(this).parent().siblings().find("textarea").val();
        }

        popHelper.show( con , data );
        
        return false;
    });

    LP.action('popclose' , function(){
        popHelper.hide();
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

    // LP.action('pop-press-item' , function(){
    //     var tpl = '<div class="popshade"></div>\
    //             <div class="pop">\
    //                 <div class="popclose" data-a="popclose"></div>\
    //                 <div class="poppiccon">\
    //                     <div class="picoperate cs-clear">\
    //                         <a href="#" class="picopsized"></a>\
    //                         <a href="#" class="picopsizeup"></a>\
    //                         <a href="#" class="picopdown"></a>\
    //                     </div>\
    //                     <img src="#[img]" alt="">\
    //                 </div>\
    //             </div>';
    //     var html = LP.format( tpl , {img: $(this).data('press')} );
    //     $(html).appendTo( document.body ).hide().fadeIn();

    //     return false;
    // });

    LP.action('newsletter' , function(){
        popHelper.show( $('#newsletter').html() );
        return false;
    });

    LP.action('i-want-to-buy' , function( data ){
        var link = $(this).closest('li').find('a').attr('href');
        History.replaceState( { prev: location.href, needAjax: false } , undefined , link  );

        var data = eval('(' + $(this).siblings('textarea').val() + ')');
        popHelper.show( LP.format( $('#i_want_to_buy').html() , data ) );
        $('.pop').find('.popcon').css('width','100%').css('padding',0);

        // prepare images
        var aHtml = [];
        $.each(data.pics , function( i , pic ){
            aHtml.push(LP.format( '<li style="float:left;"><img src="#[pic]" width="100%"/></li>' , {pic:pic}));
        });
        $('.pop .slide-con-inner').html( aHtml.join('') );

        initHoriSlider( $('.pop .js-horizontal-slide') , function( index ){
            $('.product-img-slide-num').html( index + 1 + '/' + data.pics.length );
        } );

        LP.use('form' , function(){
            $('.pop form').ajaxSubmit({
                type: 'post',
                dataType: 'json',
                success: function( data ){
                    var $pop = $('.pop');
                    if( data.status == 0 ){
                        $pop.find('.form-submit-tip')
                            .html( data.message )
                            .fadeIn();
                        $form.slideUp();

                        setTimeout(function(){
                            $pop.find('.popclose')[0].click();
                        } , 3000);
                    } else {
                        $pop.find('.form-submit-tip')
                            .html( data.message )
                    }
                }
            });
        });
        return false;
    });

    LP.action('newsletter-send' , function(){
        var $pop = $(this).closest('.pop');
        $pop.find('#name-tip,#email-tip,#phone-tip').html('');
        var $form = $(this).closest('form');
        var data = LP.query2json( $form.serialize() );
        var error = false;
        if( !data.name ){
            $pop.find('#name-tip').html( $pop.find('input[name="name"]').data('required') );
            error = true;
        }
        if( !data.email || !data.email.match( /[a-zA-Z0-9\-\._]+@(\w+\.)+\w+/ ) ){
            $pop.find('#email-tip').html( $pop.find('input[name="email"]').data('required') );
            error = true;
        }

        if( !data.phone ){
            $pop.find('#phone-tip').html( $pop.find('input[name="phone"]').data('required') );
            error = true;
        }
        if( error ){
            return false;
        }
        LP.use('form' , function(){
            $form.ajaxSubmit({
                type: 'post',
                dataType: 'json',
                success: function( data ){
                    if( data.status == 0 ){
                        $pop.find('.form-submit-tip')
                            .html( data.message )
                            .fadeIn();
                        $form.slideUp();

                        setTimeout(function(){
                            $pop.find('.popclose')[0].click();
                        } , 3000);
                    } else {
                        $pop.find('.form-submit-tip')
                            .html( data.message )
                    }
                }
            });
        });
        return false;
    })

    LP.action('contact-submit' , function(){
        var $form = $(this).closest('form');
        $form.find('#name-tip,#email-tip,#message-tip').html('');
        var data = LP.query2json( $form.serialize() );
        $('#other-error').html('');
        if( !data.poliry ){
            $('#other-error').html( $form.find('input[name="poliry"]').data('required') );
            return false;
        }
        var error = false;
        if( !data.name ){
            $form.find('#name-tip').html( $form.find('input[name="name"]').data('required') );
            error = true;
        }
        if( !data.email || !data.email.match( /[a-zA-Z0-9\-\._]+@(\w+\.)+\w+/ ) ){
            $form.find('#email-tip').html( $form.find('input[name="email"]').data('required') );
            error = true;
        }

        if( !data.message ){
            $form.find('#message-tip').html( $form.find('textarea[name="message"]').data('required') );
            error = true;
        } else if( data.message.length > $form.find('textarea[name="message"]').data('max-length') ){
            $form.find('#message-tip').html( $form.find('textarea[name="message"]').data('max-length-tip') );
            error = true;
        }


        if( error ){
            return false;
        }

        $('.loading-gif').fadeIn();
        LP.use('form' , function(){
            $form.ajaxSubmit({
                type: 'post',
                dataType: 'json',
                success: function( data ){
                    $('.loading-gif').fadeOut();
                    if( data.status == 0 ){
                        $form.fadeOut(function(){
                            $('<div class="success-tip"></div>').insertAfter( $form )
                                .html( data.message );

                            $('html,body').animate({
                                scrollTop: $('.success-tip').offset().top - 200
                            } , 500);
                        });
                    } else {
                        $('#other-error').html( data.message );
                    }
                }
            });
        });
        return false;
    });


    LP.action('contact-me-back' , function(){
        var $form = $(this).closest('form');
        var $pop = $(this).closest('.pop');
        $form.find('#name-tip,#email-tip,#phone-tip').html(' ');
        
        var data = LP.query2json( $form.serialize() );
        var error = false;
        if( !data.name ){
            $form.find('#name-tip').html( $form.find('input[name="name"]').data('required') );
            error = true;
        }
        if( !data.email || !data.email.match( /[a-zA-Z0-9\-\._]+@(\w+\.)+\w+/ ) ){
            $form.find('#email-tip').html( $form.find('input[name="email"]').data('required') );
            error = true;
        }

        if( !data.phone ){
            $form.find('#phone-tip').html( $form.find('input[name="phone"]').data('required') );
            error = true;
        }
        if( error ){
            return false;
        }
        LP.use('form' , function(){
            $form.ajaxSubmit({
                type: 'post',
                dataType: 'json',
                success: function( data ){
                    if( data.status == 0 ){
                        $pop.find('.form-submit-tip')
                            .html( data.message )
                            .fadeIn();
                        $pop.find('h3, p').hide();
                        $form.slideUp();

                        setTimeout(function(){
                            $pop.find('.popclose')[0].click();
                            $pop.find('h3, p').show();
                        } , 3000);
                    } else {
                        $pop.find('.form-submit-tip')
                            .html( data.message );
                    }
                }
            });
        });

        return false;
    });

    LP.action('picopsizeup' , function(){
        var $img = $(this).closest('.pop')
            .find('img');

        $img.width( ( $img.width() / $(window).width() * 1.1 ) * 100 + '%' );

        return false;
    });

    LP.action('picopsized' , function(){
        var $img = $(this).closest('.pop')
            .find('img');

        $img.width( ( $img.width() / $(window).width() * 0.9 ) * 100 + '%' );

        return false;
    });




    LP.action('show-news' , function(){
        // scroll to top 
        $('html,body').animate({
            scrollTop: 0
        } , 500);

        var $html = $( $(this).find('script').html() )
            .appendTo( $('.picinfor').html('') )
            .hide()
            .fadeIn( 500 );

        // init slider
        initSlider( $html.find('.slide') );
        $(window).trigger('resize');

        return false;
    });

    LP.action('page-next' , function(){
        if( $(this).data('url') ){
            pageManager.gotoPage( $(this).data('url') );
            return false;
        }
        var href = location.href;//.replace(/#.*/ , '');
        var $links = $('.footer a[data-a="nav-link"]');
        $links.each(function( i ){
            if( href.indexOf( $(this).attr('href') ) >= 0 ){
                if( $links.eq(i + 1).get(0) ){
                    var next = $links.eq(i + 1).attr('data-next');
                    if( next ){
                        pageManager.gotoPage( next );
                    } else {
                        $links.eq(i + 1).get(0).click();
                    }
                }
                return false;
            }
        });

        return false;
    });

    LP.action('page-prev' , function(){
        if( $(this).data('url') ){
            pageManager.gotoPage( $(this).data('url') );
            return false;
        }
        var href = location.href;//.replace(/#.*/ , '');
        var $links = $('.footer a[data-a="nav-link"]');
        $links.each(function( i ){
            if( href.indexOf( $(this).attr('href') ) >= 0 ){
                if( $links.eq(i - 1).get(0) )
                    $links.eq(i - 1).get(0).click();
                return false;
            }
        });

        return false;
    });

    
    // 多语言切换
    LP.action('chang-lang' , function(){
        LP.setCookie( "lang", $(this).data("lang"), 0, "/");
        LP.reload();
        return false;
    });

    LP.action('search-type' , function( data ){
        switch( data.type ){
            case 'collection':
            case 'craft':
                $('#search-result').children()
                    .filter('[data-type="' + data.type + '"]')
                    .fadeIn()
                    .end()
                    .filter('[data-type!="' + data.type + '"]')
                    .fadeOut();
                break;
            default:
                $('#search-result').children()
                    .fadeIn();
        }

        $(this).addClass('on')
            .siblings()
            .removeClass('on');

        return false;
    });


    LP.action('loadmore-press' , function(){
        var year = $.trim( $('.newsoldertime li.on').html() );
        var $dom = $('.press-list[data-year="' + year + '"]');
        var page = $dom.data('page') || 1;
        loadPressData( year , page );
        return false;
    });





    //***** fix mobile
    LP.action('m-nav' , function(){
        $('.head-inner').addClass('nav-opened');
        $('.m-nav').attr('data-a' , 'm-nav-hide' );
        $('.nav,.hd_language').show().css('left' , 0);
        if( $('.m-mask').length ){
            $('.m-mask').show();
        } else {
            $('<a href="#" class="m-mask" data-a="m-nav-hide"></a>').appendTo( document.body );
        }
    });

    LP.action('m-nav-hide' , function(){
        $('.head-inner').removeClass('nav-opened');
        $('.m-nav').attr('data-a' , 'm-nav' );
        $('.m-mask').remove();
        $('.nav,.hd_language,.nav-pop').hide();

        return false;
    });

    LP.action('m-nav-back' , function(){
        $(this).closest('.nav-pop').animate({left: '100%'} , 500);
        $('.nav,.hd_language').animate({left: 0} , 500);

        return false;
    });
});
