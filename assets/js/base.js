var imgs = {
	play: './assets/images/play.png',
	logo: './assets/images/logo.png',
	bg: './assets/images/bg1.jpg',
	arrow1: './assets/images/arron1.png',
	qrcode: './assets/images/qrcode1.png',
	hand: './assets/images/hand.png',
	like: './assets/images/like.png',
	like1: './assets/images/like1.png',
	haowai: './assets/images/haowai.png',
	bottom: './assets/images/bottom.png',
	brage: './assets/images/brage.png',
	imgBg: './assets/images/img-bg.jpg',
	qrcode: './assets/images/qrcode.jpg',
	border: './assets/images/border1.png',
	border1: './assets/images/border2.png',
	collectBtn: './assets/images/collect-btn.png',
	edit: './assets/images/edit.jpg',
	wx: './assets/images/wx2.jpg',

}

var arr = [];
for (var attr in imgs) {
	arr.push(imgs[attr]);
}
var loading = function(arr, fn, fnEnd) {
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
}