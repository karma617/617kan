<template>
	<view class="pages">
		<view class="cu-bar bg-my-light-black">
			<view class="action">
				<text class="cuIcon-title text-my-red"></text>如果您在使用过程中发现软件问题或者有好的建议，亦或求片，都可以在这里进行反馈：
			</view>
		</view>
		<view class="cu-form-group margin-top">
			<view class="title">反馈类型</view>
			<picker @change="PickerChange" :value="select" :range="type">
				<view class="picker">
					{{type[select]}}
				</view>
			</picker>
		</view>
		<view class="cu-form-group align-start solid-bottom">
			<view class="title">反馈内容</view>
			<textarea maxlength="200" v-model="formData.content" placeholder="请输入内容"></textarea>
		</view>
		<view class="cu-bar bg-my-light-black">
			<view class="action">
				图片上传
			</view>
			<view class="action">
				{{imgList.length}}/4
			</view>
		</view>
		<view class="cu-form-group margin-top">
			<view class="grid col-4 grid-square flex-sub">
				<view class="bg-img" v-for="(item,index) in imgList" :key="index" @tap="ViewImage" :data-url="imgList[index]">
				 <image :src="imgList[index]" mode="aspectFill"></image>
					<view class="cu-tag bg-red" @tap.stop="DelImg" :data-index="index">
						<text class='cuIcon-close'></text>
					</view>
				</view>
				<view class="solids" @tap="ChooseImage" v-if="imgList.length<4">
					<text class='cuIcon-cameraadd'></text>
				</view>
			</view>
		</view>
		<view class="cu-form-group margin-top solid-bottom" v-if="!isLogin">
			<view class="title">联系方式</view>
			<input placeholder="请输入QQ号或者手机号" type="number" v-model="formData.contact"></input>
		</view>
		<view class="padding flex flex-direction">
			<button class="cu-btn bg-my-red margin-tb-sm lg" @click="save">提交</button>
		</view>
	</view>
</template>

<script>
	import { pathToBase64, base64ToPath } from 'image-tools';
	export default {
		data() {
			return {
				isLogin: false,
				select: 0,
				type: ['问题反馈', '意见建议', '我要求片'],
				imgList: [],
				formData: {
					type: '问题反馈',
					content: '',
					imgs: [],
					contact: ''
				},
				imgTmp: null
			}
		},
		mounted() {
			this.init();
		},
		methods:{
			init() {
				let userinfo = uni.getStorageSync('userInfo');
				if (userinfo) {
					this.isLogin = true;
				}
			},
			save () {
				let _this = this;
				if (_this.formData.content.length <= 0) {
					uni.showToast({title: '请填写反馈内容', icon: 'none'});
					return false;
				}
				if (!_this.isLogin && _this.formData.contact.length <= 0) {
					uni.showToast({title: '请填写联系方式', icon: 'none'});
					return false;
				}
				if (_this.formData.imgs.length > 0) {
					_this.formData.imgs = _this.formData.imgs.join(',');
				}
				_this.$http.post('/user/user/feedback', _this.formData).then((res)=>{
					uni.showToast({title: res.msg, icon: 'none'});
					if (res.code == 200) {
						_this.formData = {
							type: '问题反馈',
							content: '',
							imgs: [],
							contact: ''
						};
						_this.imgList = [];
					}
				}).catch((err)=>{
					
				})
			},
			PickerChange(e) {
				this.select = e.detail.value;
				this.formData.type = this.type[e.detail.value];
			},
			ChooseImage() {
				let _this = this;
				uni.chooseImage({
					count: 4,
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album'], //从相册选择
					success: (res) => {
						if (_this.imgList.length != 0) {
							_this.imgList = _this.imgList.concat(res.tempFilePaths)
						} else {
							_this.imgList = res.tempFilePaths
						}
						_this.imgToBase64(res);
					}
				});
			},
			ViewImage(e) {
				uni.previewImage({
					urls: this.imgList,
					current: e.currentTarget.dataset.url
				});
			},
			DelImg(e) {
				this.imgList.splice(e.currentTarget.dataset.index, 1);
				this.formData.imgs.splice(e.currentTarget.dataset.index, 1);
			},
			imgToBase64 (chooseImageRes) {
				let _this = this;
				// #ifdef APP-PLUS
					for (let i=0; i <= chooseImageRes.tempFilePaths.length; i++) {
						plus.io.resolveLocalFileSystemURL(chooseImageRes.tempFilePaths[i], function(entry){
							entry.file(function(file){
								var fileReader = new plus.io.FileReader();
								fileReader.readAsDataURL( file );
								fileReader.onloadend = function(evt) {
									// 上传图片 ，返回服务器图片路径
									_this.checkImgByHttp(evt.target.result);
								}
							})
						})
					}
				// #endif
				
				// #ifdef MP-WEIXIN
					uni.getFileSystemManager().readFile({
						filePath: chooseImageRes.tempFilePaths[0], //选择图片返回的相对路径
						encoding: 'base64', //编码格式
						success: res => { //成功的回调
							let base64 = 'data:image/jpeg;base64,' + res.data //不加上这串字符，在页面无法显示的哦
							_this.checkImgByHttp(base64);
						}
					})
				// #endif
				
				// #ifdef H5
					pathToBase64(chooseImageRes.tempFilePaths[0])
					.then(base64 => {
						_this.checkImgByHttp(base64);
					})
				// #endif
			},
			uploadImg (img) {
				let _this = this;
				let formValue = {
					code: img,
					type: 'feedback'
				}
				_this.$http.post('/attachment/attachment/uploadImg', formValue).then((res)=>{
					if (res.code == 200) {
						_this.formData.imgs = _this.formData.imgs.concat(res.data.file);
					}
				}).catch((err)=>{
					
				})
			},
			checkImgByHttp (imgs){
				let _this = this;
				// https检测 用时较长
				uni.showLoading({
					title: '加载中...',
					mask: true
				})
				_this.$http.post('/index/home/checkImg', {
					str: imgs,
				}).then((res)=>{
					uni.hideLoading()
					if (200 != res.code) {
						uni.showModal({
							title: '警告',
							content: '图片含有违法信息',
							showCancel: false,
						})
						return false;
					} else {
						_this.uploadImg(imgs);
					}
				}).catch((res) => {
					uni.hideLoading()
					uni.showToast({
						title: '网络错误',
						icon: 'none'
					})
				})
			}
		}
	}
</script>

<style>
	.solids::after {
		border: 1rpx solid #45506a;
	}
</style>
