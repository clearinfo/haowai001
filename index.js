import Vue from "vue";
import Index from './components/index/index';
import Obserable from './components/lib/obserable';
import imgs from './components/lib/assets'
import zmitiUtil from './components/lib/util.js'
import $ from 'jquery';
import './components/lib/touch.js';


var obserable = new Obserable();


//Vue.config.productionTip = false

/* eslint-disable no-new */

new Vue({
	data: {
		obserable,
		rotate: false,
		imgs,
		showMask: false,
		viewH: document.documentElement.clientHeight,
		isShare: false,
		show: false,
		periodsUpper: ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十'],
		username: '',
		pv: 0,
		totalpv: 0, //几期的总浏览量
		randomPv: 0,
		width: 0,
		loaded: false,
		playStyle: {

		}
	},
	el: '#app',
	/*<audio ref='audio' src='./assets/music/bg.mp3'  loop></audio>*/
	template: `<div>
		<Index :pv="pv" :totalpv='totalpv' :randomPv='randomPv' :obserable='obserable'></Index>
		
		<div hidden @click='toggleMusic' class='zmiti-play' :class='{"rotate":rotate}' :style="playStyle">
			<img  :src='imgs.play'/>
		</div>
		<div  v-if='!loaded' :style='{background:"#158ae4"}' class='zmiti-loading lt-full'>
			<div class='zmiti-loading-ui'>
				 <a href="#">
			  		<section class='zmiti-head' :style="{background:'url(./assets/images/logo.png) no-repeat center / cover'}"></section>
			        <div class="line1"></div>
			        <div class="line2"></div>
			        <div class="line3"></div>
					<div class='zmiti-progress'>{{width}}%</div>
			    </a>
			</div>
			<img style='position:absolute;z-index:10;' :src="imgs.loading1" alt="" />
		</div>
	</div>`,
	methods: {

		loading: function(arr, fn, fnEnd) {
			var len = arr.length;
			var count = 0;
			var i = 0;

			function loadimg() {
				if (i === len) {
					return;
				}
				var img = new Image();
				img.onload = img.onerror = function() {
					count++;
					if (i < len - 1) {
						i++;
						loadimg();
						fn && fn(i / (len - 1), img.src);
					} else {
						fnEnd && fnEnd(img.src);
					}
				};
				img.src = arr[i];
			}
			loadimg();
		},
		toggleMusic() {
			var music = this.$refs['audio'];
			music[music.paused ? 'play' : 'pause']()
		},
		updatePv() {

			$.ajax({
				url: window.protocol + '//api.zmiti.com/v2/custom/update_pvnum/',
				type: 'post',
				data: {
					customid: window.zmitiConfig.customid
				}
			}).done((data) => {
				if (data.getret === 0) {
					this.pv = data.totalpv;
					this.randomPv = data.randtotalpv;
					var totalpv = this.randomPv;

					this.totalpv = totalpv;
					var i = 0;
					window.zmitiConfig.prevCustomIds = window.zmitiConfig.prevCustomIds || [];
					window.zmitiConfig.prevCustomIds.forEach((customid) => {
						$.ajax({
							url: window.protocol + '//api.zmiti.com/v2/custom/get_customdetial/',
							type: 'post',
							data: {
								customid: customid,
							}

						}).done((data) => {
							if (data.getret === 0) {
								//console.log(data);
								totalpv += data.detial.rtotalpv;
								i++;
								if (i >= window.zmitiConfig.prevCustomIds.length) {
									//console.log(totalpv)
									zmitiUtil.wxConfig(window.zmitiConfig.shareTitle.replace(/{{totalPv}}/ig, totalpv),
										window.zmitiConfig.shareDesc.replace(/{{periods}}/ig, this.periodsUpper[window.zmitiConfig.periods - 1]).replace(/{{pv}}/ig, this.randomPv));
								}

							}
						})
					});

					if (window.zmitiConfig.prevCustomIds.length <= 0) {
						zmitiUtil.wxConfig(window.zmitiConfig.shareTitle.replace(/{{totalPv}}/ig, totalpv),
							window.zmitiConfig.shareDesc.replace(/{{periods}}/ig, this.periodsUpper[window.zmitiConfig.periods - 1]).replace(/{{pv}}/ig, this.randomPv));
					}



				}
			});
		}
	},
	components: {
		Index,

	},
	mounted() {


		var src = (zmitiUtil.getQueryString('src'));

		this.isShare = src;

		this.show = true;

		this.src = src;


		this.loading(arr, (s) => {
			this.width = s * 100 | 0;

		}, () => {
			this.loaded = true;


		})

		obserable.on('showShare', () => {
			this.showMask = true;
		})


		obserable.on('setPlay', (data) => {

			this.playStyle = data;

		});
		/*
				$(this.$refs['audio']).on('play', () => {
					this.rotate = true;
				}).on('pause', () => {
					this.rotate = false;
				});




				this.$refs['audio'].volume = .3;
				this.$refs['audio'].play();
				var s = this;
				document.addEventListener("WeixinJSBridgeReady", function() {
					WeixinJSBridge.invoke('getNetworkType', {}, function(e) {
						s.$refs['audio'].play();
					});
				}, false)

				obserable.on('toggleBgMusic', (data) => {
					this.$refs['audio'][data ? 'play' : 'pause']();
				});*/

		this.updatePv();

		if (this.isShare) {
			setTimeout(() => {
				obserable.trigger({
					type: 'showIndexApp',
					data: {
						src
					}
				})
			}, 100)
		} else {

			//zmitiUtil.getOauthurl();
			//zmitiUtil.wxConfig(document.title, window.desc);
		}
	}
})