<template>
	<view class="bg-white" :style="bodyMinHeight">
		<web-view :style="viewboxStyle" :src="videoUrl"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				videoUrl: '',
				bodyMinHeight: '',
				gobackView: null,
				windowInfo: {
					statusBarHeight: 0,
					windowHeight: 0,
					windowWidth: 0,
					trueHeight: 0
				},
				screenInfo: null,
				viewboxStyle: ''
			}
		},
		onUnload() {
			// #ifdef APP-PLUS
				this.gobackView.close();
				plus.webview.close('web-view-video');
			// #endif
		},
		mounted() {
			this.videoUrl = uni.getStorageSync('webVideoUrl');
			this.uiInit();
		},
		methods: {
			screenListen(){
				
			},
			goback(){
				uni.navigateBack();
			},
			uiInit () {
				var _this = this;
				const res = uni.getSystemInfoSync();
				_this.screenInfo = res;
				_this.windowInfo.statusBarHeight = res.statusBarHeight;
				_this.windowInfo.windowHeight = res.windowHeight;
				_this.windowInfo.windowWidth = res.windowWidth;
				_this.windowInfo.trueHeight = _this.windowInfo.windowHeight - _this.windowInfo.statusBarHeight;
				
				_this.bodyMinHeight = 'min-height:' + _this.windowInfo.windowHeight + 'px';
				
				_this.drawBackBtn();
				// _this.init();
				// // #ifdef APP-PLUS
				// 	var currentWebview = _this.$mp.page.$getAppWebview(); //获取当前页面的webview对象
				// 	setTimeout(function() {
				// 		let wv = currentWebview.children()[0];
				// 		console.log(_this.windowInfo.statusBarHeight);
				// 		wv.setStyle({top:_this.windowInfo.statusBarHeight})
				// 	}, 1000); //如果是页面初始化调用时，需要延时一下
				// // #endif
			},
			init() {
				uni.showToast({
					title: '解析中，请稍候...',
					icon: 'none',
					duration: 900
				})
				var _this = this;
				// #ifdef APP-PLUS
					// 创建webview对象
					let webviewVideo = plus.webview.create(_this.videoUrl, 'web-view-video', {
						'height': _this.windowInfo.trueHeight + 'px',
						'width': "100%",
						'top': _this.windowInfo.statusBarHeight + 'px',
						'progress':{
							color:'#0080FF',
							height: '5px'
						}
					});
					setTimeout(function(){
						// 展示webview
						plus.webview.show(webviewVideo, 'auto', 600, function(){
							_this.drawBackBtn();
						});
					}, 800)
				// #endif
			},
			drawBackBtn () {
				var _this = this;
				// 画返回按钮
				_this.gobackView = new plus.nativeObj.View('goBackBtn',
						{
							top: _this.windowInfo.statusBarHeight + 15 + 'px',
							left:'20px',
							height:'35px',
							width:'35px',
							backgroundColor: '#333',
							opacity: 0.14
						},
						[
							{
								id:'img',
								tag:'img',
								src:'/static/goback.png',
								position:{
									top:'7.2px',
									left:'6px',
									width:'20px',
									height:'20px',
								},
							},
						]
					);
				// 显示按钮
				_this.gobackView.show();
				// 监听按钮点击事件
				_this.gobackView.addEventListener("click", function(){
					_this.goback();
				})
			}
		}
	}
</script>

<style>
</style>
