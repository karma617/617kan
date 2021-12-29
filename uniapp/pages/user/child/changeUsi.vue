<template>
	<view class="pages">
		<view class="cu-form-group solid-bottom">
			<view class="title">头像</view>
			<view @click="changeAvatar" class="cu-avatar round" v-if="!avatar"
			:style="[{ backgroundImage:'url(' + avatarUrl + ')' }]">
			</view>
			<view @click="changeAvatar" class="cu-avatar round" v-else
			:style="[{ backgroundImage:'url(' + avatar + ')' }]">
			</view>
		</view>
		<view class="cu-form-group solid-bottom">
			<view class="title">昵称</view>
			<input placeholder="昵称" v-model="userInfo.nick"></input>
		</view>
		<view class="padding flex flex-direction">
			<button class="cu-btn bg-my-red margin-tb-sm lg" @click="save">保存</button>
		</view>
	</view>
</template>

<script>
	import { pathToBase64, base64ToPath } from 'image-tools';
	export default{
		data() {
			return {
				userInfo: {},
				avatar: '',
				avatarUrl: "../../../static/avatar.png",
				imageUrl: this.$helper.imageUrl,
				apiImageUrl: this.$helper.apiImageUrl,
				uploadAvatar: ''
			}
		},
		onShow() {
			this.init();
		},
		methods:{
			init() {
				let userinfo = uni.getStorageSync('userInfo');
				if (userinfo) {
					this.userInfo = userinfo;
					this.avatar = this.userInfo.avatar.search('http') >= 0 ? this.userInfo.avatar : this.apiImageUrl + this.userInfo.avatar;
				} else{
					uni.navigateTo({
						url: '/pages/user/login'
					})
				}
			},
			changeAvatar () {
				let _this = this;
				uni.chooseImage({
					count: 1, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album'], //从相册选择
					success: (res) => {
						_this.userInfo.avatar = res.tempFilePaths;
						_this.avatar = res.tempFilePaths;
						_this.imgToBase64(res);
					}
				});
			},
			save () {
				let _this = this;
				let formValue = {};
				if (_this.uploadAvatar.length > 0) {
					formValue.avatar = _this.uploadAvatar;
				}
				if (_this.userInfo.nick.length > 0) {
					formValue.nick = _this.userInfo.nick;
				}
				_this.$http.post('/user/user/changeUsi', formValue).then((res)=>{
					uni.showToast({title: res.msg, icon: 'none'});
					if (res.code == 200) {
						let usi = uni.getStorageSync('userInfo');
						usi.avatar = _this.uploadAvatar ? _this.uploadAvatar : usi.avatar;
						usi.nick = _this.userInfo.nick;
						uni.setStorageSync('userInfo', usi);
						uni.navigateBack();
					}
				}).catch((err)=>{
					
				})
			},
			imgToBase64 (chooseImageRes) {
				let _this = this;
				// #ifdef APP-PLUS
					plus.io.resolveLocalFileSystemURL(chooseImageRes.tempFilePaths[0], function(entry){
						entry.file(function(file){
							var fileReader = new plus.io.FileReader();
							fileReader.readAsDataURL( file );
							fileReader.onloadend = function(evt) {
								// 上传图片 ，返回服务器图片路径
								_this.uploadImg(evt.target.result);
							}
						})
					})
				// #endif
				
				// #ifdef MP-WEIXIN
					uni.getFileSystemManager().readFile({
						filePath: chooseImageRes.tempFilePaths[0], //选择图片返回的相对路径
						encoding: 'base64', //编码格式
						success: res => { //成功的回调
							let base64 = 'data:image/jpeg;base64,' + res.data //不加上这串字符，在页面无法显示的哦
							_this.uploadImg(base64);
						}
					})
				// #endif
				
				// #ifdef H5
					pathToBase64(chooseImageRes.tempFilePaths[0])
					.then(base64 => {
						_this.uploadImg(base64);
					})
				// #endif
			},
			uploadImg (img) {
				let _this = this;
				let formValue = {
					code: img,
					type: 'avatar'
				}
				_this.$http.post('/attachment/attachment/uploadImg', formValue).then((res)=>{
					uni.showToast({title: res.msg, icon: 'none'});
					if (res.code == 200) {
						// 表单头像路径
						_this.uploadAvatar = res.data.file;
					}
				}).catch((err)=>{
					
				})
			}
		}
	}
</script>

<style>
	.solid-bottom::after {
	    border-bottom: 0.5px solid #23273f!important;
	}
</style>
