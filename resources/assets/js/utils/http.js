export default {

	base_url: '/api',
	headers: {
		"X-Requested-With": "XMLHttpRequest",
		'Content-Type': 'application/json;charset=utf-8',
		'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		Authorization: 'Bearer ' + localStorage.getItem("user-token"),
	},

	getUrl( url )
	{
		return fetch( url ).then( response => response.json() );
	},

	get( url )
	{
		return fetch( this.base_url + url, {
				headers: this.headers,
			}).then( response => response.json() );
	},

	post( url, data )
	{
		return fetch( this.base_url + url, {
				method: 'POST',
				headers: this.headers,
				body: JSON.stringify( data )
			}).then( response => response.json() );
	},

	postFile( url, data )
	{
		let fd = new FormData();
		delete this.headers['Content-Type'];

		for ( let prop in data ) fd.append( prop, data[prop]);

		return fetch( this.base_url + url, {
				method: 'POST',
				headers: this.headers,
				body: fd
			}).then( response => response.json() );
	},

	put( url, data )
	{
		return fetch( this.base_url + url, {
				method: 'PUT',
				headers: this.headers,
				body: JSON.stringify( data )
			}).then( response => response.text() );
	},

	setToken()
	{
		this.headers.Authorization = 'Bearer ' + localStorage.getItem("user-token");
	},

	setSocketId( socketId )
	{
		this.headers['X-Socket-ID'] = socketId;
	}
}
