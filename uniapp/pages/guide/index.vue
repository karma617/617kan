<template>
    <view class="content"></view>
</template>

<script>
export default {
    onLoad() {
		this.checkGuide();
    },
    methods: {
        checkGuide() {
			let config = uni.getStorageSync('sys_config');
			// 是否显示开屏广告
			if (config.show_ad == "1") {
				// 广告页
				uni.redirectTo({
					url: '/pages/guide/list'
				});
			}else{
				uni.switchTab({
					url: '/pages/index/index'
				});
			}
        }
    }
};
</script>

<style></style>
