<script>
	export default {
		onLaunch: function() {
			// #ifndef H5
				uni.setKeepScreenOn({
					keepScreenOn: true
				});
			// #endif
			// #ifdef APP-PLUS
				if (uni.getSystemInfoSync().platform == "android") {
					this.$downloader.init({
						maxDownloadTasks: 3,		// 最大同时下载任务数
						maxDownloadThreads: 3,		// 最大下载线程数
						autoRecovery: false,		// 是否自动恢复下载
						openRetry: true,			// 下载失败是否打开重试
						maxRetryCount: 2,			// 重试次数
						retryIntervalMillis: 1000	// 每次重试时间间隔（毫秒）
					}, function(res){
						if(0 == res.code){
							// console.log(res.msg);
						}
					});
				}
			// #endif
			// #ifdef MP-WEIXIN
				// uni.getLocation({
				// 	type: 'wgs84',
				// 	success: function (res) {
				// 		console.log(res);
				// 		uni.setStorageSync('shen', 0);
				// 	},
				// 	fail(res) {
				// 		uni.setStorageSync('shen', 1);
				// 	}
				// });
			// #endif
		},
		onShow: function() {
			let paying = uni.getStorageSync('paying');
			if (paying) {
				uni.redirectTo({
					url: '/pages/pay/detail'
				})
			}
			// 刷新公共配置
			this.$http.post('/index/config/getConfig',{}).then((res)=>{
				uni.setStorageSync('sys_config', res.data);
			})
		},
		onHide: function() {
			
		}
	}
</script>

<style>
	/* #ifndef APP-PLUS-NVUE */
	@import "colorui/main.css";
	@import "colorui/icon.css";
	@import "static/css/common.css";
	/* #endif */
</style>
