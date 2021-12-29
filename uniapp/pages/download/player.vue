<template>
	<view>
		<!-- 播放器 -->
		<view class="player-box"></view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				name: ''
			}
		},
		onLoad (option) {
		    this.name = option.name;
		},
		onReady() {
			var _this = this;
			const subNVue = uni.getSubNVueById('locationPlayer');
			_this.$downloader.getFilePath(function(res){
				let src = "file://" + res + _this.name;
				subNVue.setStyle({  
				    width: uni.getSystemInfoSync().windowWidth + 'upx',  
				    height: uni.getSystemInfoSync().windowHeight + 'upx'
				})
				// 打开 nvue 子窗体
				subNVue.show('none', 300, function(){
				    subNVue.postMessage({  
				        url: src,
						title: _this.name
				    });
				});
			});
			
		}
	}
</script>

<style>
</style>
