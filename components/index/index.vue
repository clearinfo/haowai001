<template>
	<div :style="{background:createImg? 'url('+imgs.bg+') no-repeat center 100%':'transparent',backgroundSize:'200% 200%'}" class="lt-full zmiti-index-main-ui "  :class="{'show':show}">

		<transition name="zmiti-scale"
			@after-enter="afterEnter"
		 >
		    <div ref='createimgs' :style="{background: 'url('+imgs.imgBg+') no-repeat center top',backgroundSize:'cover'}" class="zmiti-createimg"  v-if='createImg'>
				<img :src="createImg" alt="">
				<div class="zmiti-border" :class='{"show":showBtns}'>
					<img :src="imgs.border">
				</div>
				<div class="zmiti-border zmiti-border1"  :class='{"show":showBtns}'>
					<img :src="imgs.border1">
				</div>
			</div>
		  </transition>
		<div v-show='!createImg'  class="lt-full" ref='page'>
			<div>
				<div class="zmiti-index-main-content"  ref='zmiti-cache-page'>
					<section >
						<div>
							<img :src="imgs.wx" v-if='!showCollect' >
							<p style="height: 1px;" v-if='showCollect'></p>
							<div  :style="{background: 'url('+imgs.bg+') no-repeat center top',paddingTop:!showCollect?'2%':'2%',backgroundSize:'cover'}">
								<span></span>
								<div style="padding-top:4vh;"></div>
								<div class="zmiti-brage">
								</div>
								<div class="zmiti-haowai">
									<img :src="imgs.brage" alt="">
								</div>
								<div class="zmiti-news-C">
									<h1>{{title}}</h1>
									<div class="zmiti-news">
										<div v-for='c in newsContent'>{{c}}</div>
									</div>
								</div>
								<div class="zmiti-index-bottom">									
									<div class="zmiti-copyright">
										<div class="zmiti-copyright-xinhua">											
											<img :src="imgs.logo" alt="">
											<div>新华社客户端</div>
										</div>
										<div class="zmiti-work-pubdate">
											
												<div>{{date}}</div>
											
										</div>										
									</div>
									
								</div>
							</div>
						</div>
						
					</section>

					<div class="zmiti-qrcode">
						<section> 
							<div>
								<div>我收藏了2018年两会号外</div>
								<div>NO.{{randomPv}}号</div>
							</div>
							<div>
								<img :src="imgs.qrcode" @touchstart='starts' />
							</div>
						</section>
					</div>
				</div>
				<div class="wx-comments"    >
				    <div class="wx-inner">
				        <div class="comm-tips">
				            <span>评论墙</span>
				            <div class="clearfix"></div>
				        </div>
				        <div class="wx-btn1">
			                <span class="wx-btn-span" @touchend='openDialog'>
			                    <span>
			                        我要上墙
			                    </span>
			                    <i><img :src="imgs.edit" alt=""></i>
			                </span>
				        </div>
				        <div class="comm-inner">
				            <ul id="wx-getmessagelist">
				                <!--留言列表-->
				                <li v-for='(item,i) in commentList'>
				                	
				                	<div class="user-face">
				                		<img :src="imgs.logo" />
				                	</div>
				                	<div class="user-con">
				                		<div class="unames">新华社网友
				                			<div class="u-dz" @touchend='like(item,i)'>
				                				<i class="icon_praise_gray">
				                					<img :src="item.isLike?imgs.like1:imgs.like" alt="">
				                				</i>
				                				<span>{{item.hymn}}</span>
				                				<div class="zmiti-add" :class="{'active':addIndex === i}">+1</div>
				                			</div>
				                		</div>
				                		<div class="utext">{{item.content}}</div>
				                		<div class="udates">{{item.createtime}}</div>
				                	</div>
				                </li>
				            </ul>

				            <div class="comm-tips">
				                <span>以上留言由新华社筛选后显示</span>
				                <div class="clearfix"></div>
				            </div>
				            <div class="hr20"></div>
				        </div>
				    </div>

				</div>
			</div>
			
		</div>

		<!--message-dialog-->
		<div id="wx-message-dialog" v-if='showDialog'>
		    <div class="wx-weui-mask"></div>
		    <div class="wx-weui-dialog">
		        <div class="wx-weui-title">2018年两会号外</div>

		        <div class="wx-weui-textarea">
		            <textarea v-model='content' placeholder=""></textarea>
		        </div>
		        <div class="wx-weui-btn2">
		            <div class="wx-weui-submit" v-tap='submitData'>提交</div>
		        </div>
		    </div>
		</div>

		 <div v-if='showLoading' class="zmiti-createimg-loading lt-full " :style="{background: 'url('+imgs.bg+') no-repeat center top',backgroundSize:'cover'}">
		 	 	<div class="loading">
		 	 		<span></span>
			        <span></span>
			        <span></span>
			        <span></span>
			        <span></span>
			        <label>正在生成号外...</label>
		 	 	</div>
		 </div>

		 <div class="zmiti-collect" @touchstart='isPress = true' @touchend='collect' :class='{"press":isPress}'  v-if='showCollect && !createImg'>
			<img :src="imgs.collectBtn" alt="">
			<span>点击收藏</span>
		</div>
		
		<div v-if='showBtns && !src' class="zmiti-share-btns">
			<div @touchend='restart' @touchstart='seePress = true' >
				<div :class='{"press":seePress}' class="">
					<img :src="imgs.collectBtn">
					<span>
						我再看看
					</span>
				</div>
			</div>
			<div @touchend='showMask' @touchstart='sharePress = true' :class='{"press":sharePress}'>
				<img :src="imgs.collectBtn">
				<span>
					分享
				</span>
			</div>
		</div>

		<div v-if='showBtns && src' class="zmiti-share-btns zmiti-share-btns1">
			<div @touchend='restart' @touchstart='seePress = true'  :class='{"press":seePress}' >
				<img :src="imgs.collectBtn">
				<span>阅读新华社号外</span>
			</div>
		</div>

		<div v-if='showMasks' @touchstart='hideMask' class="zmiti-mask" :style="{background: 'url('+imgs.arrow1+') no-repeat center top',backgroundSize:'cover'}">
			
		</div>

		<Toast :msg='toastMsg'></Toast>
		<audio src='./assets/music/1.mp3' ref='audio'></audio>
	</div>
</template>

<script>
	import './index.css';
	import imgs from '../lib/assets.js';
	import zmitiUtil from '../lib/util';
	import '../lib/html2canvas';
	import $ from 'jquery';
	import Toast from '../toast/toast';

	import IScroll from 'iscroll';
	export default {
		props:['obserable','randomPv','pv','totalpv'],
		name:'zmitiindex',
		data(){
			return{
				imgs,
				show:true,
				toastMsg:'',
				showTitle:false,
				viewW:document.documentElement.clientWidth,
				showBtns:false,
				viewH:document.documentElement.clientHeight,
				words:[],
				start:true,
				addIndex:-1,
				showMasks:false,
				count:[],
				showHand:true,
				commentList:[],
				isPress:false,
				showLoading:false,
				showCollect:true,
				periodsUpper:['一','二','三','四','五','六','七','八','九','十'],
				seePress:false,
				sharePress:false,
				num:'',
				content:'',
				periods:window.zmitiConfig.periods||1,
				title:window.zmitiConfig.title,
				date:window.zmitiConfig.date,
				height:window.zmitiConfig.height||0,
				newsContent:window.zmitiConfig.newsContent||[],
				createImg:'',//http://bluesky.zmiti.com/zmiti_ele/public/a29ea3219fddf990feb754152672071b.png',
				showDialog:false,
				src:'',
			}
		},
		components:{
			 Toast
		},
		
		methods:{
			starts(){
				
			},
			like(item,index){
				if(this.commentList[index].isLike){
					this.toast("您已经点过赞啦~");
					return;
				}
				var s = this;

				 $.ajax({
                    url: 'http://api.zmiti.com/v2/h5/click_hymn/',
                    type: 'post',
                    async: false,
                    data: {
                        qid: item.qid,
                    },
                    success: function(data) {
                        //console.log(data,'提交成功');
                        if (data.getret === 0) {
                            //列表
                            s.addIndex = index;
                            s.getCommentList(()=>{
                            	s.commentList[index].isLike = true;
                            });



                        }
                    }
                });
			},
			toast(msg='提交成功',time=2000){
				this.toastMsg = msg;
				setTimeout(()=>{
					this.toastMsg = '';
				},time)
			},
			submitData(){
				var s = this;

				if(s.content.trim().length <= 0){
					s.toast('评论内容不能为空');
					return;
				}

				 $.ajax({
	                url: 'http://api.zmiti.com/v2/h5/add_question/',
	                type: 'post',
	                async: false,
	                data: {
	                    sex: 0,
	                    content: s.content,
	                    hymn: Math.floor(Math.random() * 20 + 1),
	                    classid: 15,
	                    sort: 1001,
	                    contenttype: 1, //评论
	                    username: window.nickname || '新华社网友',
	                    headimage: window.headimgurl,
	                    worksclassid: 1 //十九大
	                },
	                success: function(data) {
	                    if (data.getret === 0) {
	                       	s.showDialog = false;
	                       	s.toast();
	                    }
	                }
	            });
			},
			collect(){//收藏
				this.isPress = false;
				setTimeout(()=>{
					this.showCollect = false;
				},100)
				this.html2img();
			},
			openDialog(){
				this.showDialog = true;
			},
			numstart(){
				//this.num =  1;
			},
			hideMask(){
				
				this.showMasks = false;
			},
			showMask(){
				this.sharePress = false;
				setTimeout(()=>{
					this.showMasks = true;
				},200)
			},
			restart(){
				this.seePress = false;
				setTimeout(()=>{
					window.location.href = window.location.href.split('?')[0];
				},200)
			},
			afterEnter(){
				this.showBtns = true;
			},
			html2img(){
				var s = this;

				var {obserable} = this;

				this.scroll.scrollTo(0,0,0);
				//document.title = '开始截图....'
				setTimeout(()=>{
					//this.showLoading = true;
					var ref = 'zmiti-cache-page';
					var dom = this.$refs[ref];
					html2canvas(dom,{
						useCORS: true,
						onrendered: function(canvas) {
					        var src = canvas.toDataURL();
					        s.createImg = src;
		             		s.showBtns = true;
		             		s.showLoading = false;

		             		setTimeout(()=>{
		             			//document.title=s.viewH+','+(s.$refs['createimgs'].offsetHeight*1.2)
								s.$refs['createimgs'].style.WebkitTransform = 'scale('+s.viewH/(s.$refs['createimgs'].offsetHeight*1.2)+')'

								s.$refs['audio'].play();
							},100);

							zmitiUtil.wxConfig(window.zmitiConfig.shareTitle.replace(/{{totalPv}}/ig, s.totalpv),
							window.zmitiConfig.shareDesc.replace(/{{periods}}/ig, s.periodsUpper[window.zmitiConfig.periods - 1]).replace(/{{pv}}/ig, s.randomPv));
					        return;
					        /*$.ajax({
					          //url: window.protocol+'//api.zmiti.com/v2/share/base64_image/',
					          url:window.protocol+'//'+window.server+'.zmiti.com/v2/share/base64_image/',
					          type: 'post',
					          data: {
					            setcontents: url,
					            setwidth: dom.clientWidth,
					            setheight:dom.clientHeight
					          },
					          success: function(data) {
					          	//alert('data.getret =>'+data.getret)
					          	//document.title = '截图成功....'
					            if (data.getret === 0) {
					            	//s.deleteImg(dt.img);

					              var src = data.getimageurl;
					             	
					             	var img = new Image();
					             	img.onload = function (argument) {
					             		// body...
					             		s.createImg = src;
					             		s.showBtns = true;
					             		s.showLoading = false;

					             		setTimeout(()=>{
					             			//document.title=s.viewH+','+(s.$refs['createimgs'].offsetHeight*1.2)
											s.$refs['createimgs'].style.WebkitTransform = 'scale('+s.viewH/(s.$refs['createimgs'].offsetHeight*1.2)+')'

											s.$refs['audio'].play();
										},100)
					             	};
					             	img.src =src;
	
									var url = window.location.href.split('#')[0];



									

									url = zmitiUtil.changeURLPar(url,'src',src);

									
								       
					            }

					          }
					        })*/

					      },
					      width: dom.clientWidth,
					      height:dom.clientHeight
					})
				},100)
			},
			getCommentList(fn){
				if(this.createImg){
					return;
				}
				$.ajax({
					url: 'http://api.zmiti.com/v2/h5/select_question/',
			        type: "POST",
			        async: false,
			        cache: false,
			        data: {
			            worksclassid: 1,
			            status: 1,
			        },
				}).done((data)=>{
					if(data.getret === 0){
						this.commentList = data.questionlist;
						fn && fn()
					}
				})
			}
			
		},
		mounted(){

			var {obserable} = this;


			this.getCommentList(()=>{
				 this.scroll = new IScroll(this.$refs['page'],{
                	//scrollbars:true
                });

				setTimeout(()=>{
					 this.scroll.refresh();
				},1000)

			});

			
			


			obserable.on('showIndexApp',(data)=>{
				this.show = true;
				if(data){
					var s = this;
					this.createImg = data.src;
					this.src = data.src;

					setTimeout(()=>{
						this.$refs['createimgs'].style.WebkitTransform = 'scale('+this.viewH/2000+')'
					},10);

					var url = window.location.href.split('#')[0];
						url = zmitiUtil.changeURLPar(url,'src',this.src);

						zmitiUtil.wxConfig(window.zmitiConfig.shareTitle.replace(/{{totalPv}}/ig, s.totalpv),
							window.zmitiConfig.shareDesc.replace(/{{periods}}/ig, this.periodsUpper[window.zmitiConfig.periods - 1]).replace(/{{pv}}/ig, s.randomPv),url);
				}
			})
 

		}
	}
</script>

<style scoped="">
	.loading{
            width: 5rem;
            left: 2.5rem;
            height: 30px;
            margin: 0 auto;
            margin-top:40px;
            text-align: center;
            position: absolute;
            top: 6rem;
            z-index: 0;
        }
        .loading span{
            display: inline-block;
            width: 30px;
            height: 100%;
            margin-right: 10px;
            background: #be0000;
            -webkit-animation: load 1.04s ease infinite;
        }
        .loading label{
        	display: block;
        	color:#be0000;
        }
        .loading span:last-child{
            margin-right: 0px; 
        }
        @-webkit-keyframes load{
            0%{
                opacity: 1;
            }
            100%{
                opacity: 0;
            }
        }
        .loading span:nth-child(1){
            -webkit-animation-delay:0.2s;
        }
        .loading span:nth-child(2){
            -webkit-animation-delay:0.4s;
        }
        .loading span:nth-child(3){
            -webkit-animation-delay:0.6s;
        }
        .loading span:nth-child(4){
            -webkit-animation-delay:0.8s;
        }
        .loading span:nth-child(5){
            -webkit-animation-delay:1s;
        }
</style>