export default class ZmitiMove {
	constructor(option) {
		this.obj = option.obj;
		this.speedX = (Math.random() / 12 + .01) * (Math.random() - .5 > 0 ? 1 : -1);
		this.speedY = (Math.random() / 12 + .01) * (Math.random() - .5 > 0 ? 1 : -1);
		this.live = (Math.random() * 4 + 4) | 0;
		this.transX = this.transY = 0;
	}

	move() {
		this.transX += this.speedX;
		this.transY += this.speedY;
		if (this.transX > this.live || this.transX < -this.live) {
			this.speedX *= -1;
		}
		if (this.transY > this.live || this.transY < -this.live) {
			this.speedY *= -1;
		}
		this.obj.style.WebkitTransform = `translate3d(${this.transX}px,${this.transY}px,0)`;

	}
}