<template>
	<view class="pages">
		<view class="solid-bottom">
			<view class="cu-form-group">
				<!-- <input placeholder="请输入视频链接地址" @focus="clear" v-model="videoUrl" confirm-type="解析" @confirm="goPlay"></input> -->
				<textarea maxlength="-1" :auto-height="true" placeholder="自动读取剪贴板地址或可手动输入视频地址" @click="clear" @focus="clear" v-model="videoUrl" @confirm="goPlay"></textarea>
			</view>
			<view class="padding flex flex-direction">
				<button class="cu-btn bg-blue margin-tb-sm lg" @click="goPlay">解析</button>
			</view>
		</view>
		<view class="solid-bottom">
			<view class="cu-bar text-my-white">
				<view class="action">
					<text class="cuIcon-title text-blue"></text>支持站点：
				</view>
			</view>
			<view class="text-my-white padding-lr">
				<view class="flex-sub text-left">
					<view class="text-df padding-5">爱奇艺、芒果视频、土豆视频、乐视视频、搜狐视频、优酷视频、PPTV、腾讯视频、咪咕视频、酷6网、暴风影音、
						华数视频、1905视频、风行视频、央视视频、天天看看、哔哩哔哩、音悦台、AcFun、秒拍、美拍、虎牙、龙珠、
						YY、一直播、映客直播、2MM、梨视频、QQ音乐MV、糖豆、360短视频、新浪视频、
						爆米花视频、酷狗MV、火猫直播、斗鱼视频、第一视频、27盘等视频网站...
					</view>
				</view>
			</view>
		</view>
		<view class="solid-bottom">
			<view class="cu-bar text-my-white">
				<view class="action">
					<text class="cuIcon-title text-blue"></text>使用说明：
				</view>
			</view>
			<view class="text-my-white padding-lr">
				<view class="flex-sub text-left">
					<view class="text-df padding-5">1、输入想要解析的视频地址（可以在视频软件中点击分享->复制链接），返回APP后视频地址会自动读取剪贴板填入，点击“解析”，等待播放器加载视频。</view>
				</view>
				<view class="flex-sub text-left">
					<view class="text-df padding-5">2、横屏播放请打开“自动旋转”。</view>
				</view>
			</view>
		</view>
		<view class="solid-bottom">
			<view class="cu-bar text-my-white">
				<view class="action">
					<text class="cuIcon-title text-blue"></text>注意事项：
				</view>
			</view>
			<view class="text-my-white padding-lr">
				<view class="flex-sub text-left">
					<view class="text-df padding-5">1、点击“解析”后，如果视频很久都加载不出来，返回再次点击“解析”，多试几次。</view>
				</view>
				<view class="flex-sub text-left">
					<view class="text-df padding-5">2、如果视频一直都无法加载，请换个网络再次尝试。</view>
				</view>
				<view class="flex-sub text-left">
					<view class="text-df padding-5">3、由于解析接口的不稳定性，若出现大量视频都无法解析的情况，请联系我们，我们将及时更新解析接口。</view>
				</view>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		data(){
			return {
				baseUrl: 'http://17kyun.com/api.php?url=',
				videoUrl: ''
			}
		},
		methods:{
			goPlay () {
				var _this = this;
				if (_this.videoUrl.length <= 0) {
					uni.showToast({title: '请填写视频地址', icon: "none"});
					return false;
				}
				_this.$http.post('/videos/videos/formatUrl',{url:_this.videoUrl}).then((res)=>{
					uni.setStorageSync('webVideoUrl', _this.baseUrl + res.data);
					uni.navigateTo({url:"../analysis/play"});
				}).catch((err)=>{
					
				})
			},
			getBaseUrl () {
				var _this = this;
				_this.$http.post('/index/config/getConfig',{}).then((res)=>{
					_this.baseUrl = res.data.base_url;
				}).catch((err)=>{
					
				})
			},
			clear () {
				this.videoUrl = '';
			}
		},
		mounted() {
			this.getBaseUrl();
		},
		onShow() {
			var _this = this;
			// #ifdef APP-PLUS
				uni.getClipboardData({
					success: function (res) {
						let rep = /(http[s]?:\/\/([\w-]+.)+([:\d+])?(\/[\w-\.\/\?%&=]*)?)/gi; 
						let v = res.match(rep);
						if (v) {
							_this.videoUrl = v[0];
						}
					}
				});
			// #endif
		}
	}
</script>