import imgs from './assets.js'
let posterList = [

	{
		pageStyle: {
			background: 'url(' + imgs.poster1 + ') no-repeat center bottom',
			backgroundSize: 'cover'
		},
		doPosterClass: '', //zmiti-do-poster1
		logoColor: "#fff",
		qrcodeStyle: {
			width: '1.8rem',
			left: '4.1rem',
			bottom: '.5rem'
		},
		showHome: true,

	}, {
		pageStyle: {
			background: 'url(' + imgs.poster2 + ') no-repeat center bottom',
			backgroundSize: 'cover'
		},
		doPosterClass: '', //zmiti-do-poster1
		logoColor: "#853c24",
		qrcodeStyle: {
			width: '1.8rem',
			left: '4.1rem',
			bottom: '1.3rem',
			border: '1px dashed #853c24'
		}
	}, {
		pageStyle: {
			background: 'url(' + imgs.poster3 + ') no-repeat center bottom',
			backgroundSize: 'cover'
		},
		doPosterClass: '', //zmiti-do-poster1
		logoColor: "#fff",
		qrcodeStyle: {
			width: '1.8rem',
			left: '4.1rem',
			top: '50%'
		}
	}, {
		pageStyle: {
			background: 'url(' + imgs.poster4 + ') no-repeat center top',
			backgroundSize: 'cover'
		},
		doPosterClass: 'zmiti-do-poster1', //
		logoColor: "#000",
		qrcodeStyle: {
			right: '.2rem',
			bottom: ".5rem"
		}
	}

];

export default posterList;