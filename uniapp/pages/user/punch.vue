<template>
	<view class="pages">
		<view class="flex-sub text-left padding-10">
			<text class="text-df text-my-white padding">{{punchText}}</text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				punchText: ''
			}
		},
		methods:{
			init () {
				let _this = this;
				_this.$http.post('/index/home/punch', {}).then((res)=>{
					if (res.code == 200) {
						_this.punchText = res.data;
					}
				}).catch((err)=>{
					
				})
			}
		},
		mounted() {
			this.init();
		}
	}
</script>

<style>
</style>
