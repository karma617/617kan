<template>
	<view class="pages" :style="bodyMinHeight">
		<block v-if="!isShowxx">
			<view class="mask" v-if="!islogin">
				<view class="text-df refresh-text text-gray">
					<image class="refresh-img" src="../../static/noinfo.png"></image>
					<view class="" >请先登录</view>
				</view>
			</view>
			<view class="mask" v-if="!liveLists">
				<view class="text-df refresh-text text-gray">
					<image class="refresh-img" src="../../static/noinfo.png"></image>
					<view class="" >敬请期待</view>
				</view>
			</view>
			<view v-else class="" v-for="(item,index) in liveLists" :key="index">
				<view v-if="item.is_top">
					<view class="cu-bar bg-my-gray margin-top">
						<view class='action'>
							<text class='cuIcon-title text-my-red'></text>{{item.name}}
						</view>
					</view>
					<view class='flex flex-wrap col-4 grid'>
						<view class="padding-xs" v-for="(items,indexs) in item.live_info" :key="indexs" @click="goDetail(items)">
							<view style="width: 100%;" class='cu-tag bg-my-red' v-if="currentTitle == items.title">{{items.title}}</view>
							<view style="width: 100%;" class='cu-tag line-my-gray' v-else>{{items.title}}</view>
						</view>
					</view>
				</view>
				<view v-else>
					<view class="cu-bar bg-my-gray margin-top">
						<view class='action'>
							<text class='cuIcon-title text-my-red'></text>{{item.name}}
						</view>
					</view>
					<view class='flex flex-wrap col-4 grid'>
						<view class="padding-xs" v-for="(items,indexs) in item.live_info" :key="indexs" @click="goDetail(items)">
							<view style="width: 100%;" class='cu-tag bg-my-gray' v-if="currentTitle == items.title">{{items.title}}</view>
							<view style="width: 100%;" class='cu-tag line-my-gray' v-else>{{items.title}}</view>
						</view>
					</view>
				</view>
			</view>
		</block>
		<block v-else>
			<view class="mask">
				<view class="text-df refresh-text text-gray">
					<image class="refresh-img" src="../../static/noinfo.png"></image>
					<view class="" >敬请期待</view>
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				bodyMinHeight: '',
				windowInfo: {
					statusBarHeight: 0,
					windowHeight: 0,
					windowWidth: 0,
					trueHeight: 0
				},
				liveLists: [],
				currentTitle: '',
				islogin: true,
				isShowxx: false
			}
		},
		mounted() {
			this.init();
		},
		onTabItemTap(e) {
			if (3 == e.index) {
				let _this = this;
				this.isLogin();
			}
		},
		onShow() {
			// #ifdef APP-PLUS
			this.isLogin();
			// #endif
		},
		methods:{
			isLogin () {
				let userinfo = uni.getStorageSync('userInfo');
				if (!userinfo) {
					this.liveLists = [];
					this.islogin = false;
				}else{
					this.islogin = true;
					if (this.liveLists.length > 0) {
						return false;
					}
					this.getLiveLists();
				}
			},
			init () {
				let _this = this;
				const res = uni.getSystemInfoSync();
				_this.windowInfo.statusBarHeight = res.statusBarHeight;
				_this.windowInfo.windowHeight = res.windowHeight;
				_this.windowInfo.windowWidth = res.windowWidth;
				_this.windowInfo.trueHeight = _this.windowInfo.windowHeight - _this.windowInfo.statusBarHeight;
				_this.bodyMinHeight = 'min-height:'+_this.windowInfo.windowHeight+'px;overflow-y: auto;';
				const config = uni.getStorageSync('sys_config');
				_this.isShowxx = isNaN(parseInt(config.show_xx)) ? true : parseInt(config.show_xx);
				_this.getLiveLists();
			},
			getLiveLists () {
				let _this = this;
				_this.$http.post('/live/live/getLiveLists',{}).then((res)=>{
					if (200 == res.code) {
						_this.liveLists = res.data;
					}else{
						uni.showToast({
							title: res.msg || '获取失败',
							icon: 'none'
						})
					}
				}).catch((err)=>{

				})
			},
			goDetail (item) {
				this.currentTitle = item.title;
				uni.setStorage({
					key: 'liveInfo',
					data: item,
					success() {
						uni.navigateTo({
							url: '/pages/live/detail'
						})
					}
				})
			}
		}
	}
</script>

<style>
</style>
