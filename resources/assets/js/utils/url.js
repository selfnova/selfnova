export default {

	getUserAvatar( id, photo )
	{
		if ( !photo ) return '/img/user.png';

		return this.buildSrc( id, 'users', photo );
	},

	getGroupAvatar( id, photo )
	{
		if ( !photo ) return '/img/user.png';

		return this.buildSrc( id, 'groups', photo );
	},

	buildSrc( id, dirName, photo )
	{
		let dir = Math.floor( id / 1000 );
		let dir2 = String(id).padStart( 6, '0' );

		return `/img/${dirName}/00${dir}/${dir2}/${photo}`;
	},
}
