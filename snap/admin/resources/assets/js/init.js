if (typeof(window.SNAP_CONFIG) == 'undefined') {
	window.SNAP_CONFIG = {
		'el': '#snap-admin',
		'urlPaths': {'admin': '/admin'},
		'environment': 'testing',
		'debug': {
			'level': 3,
			'clear': false // need for Safari
		}
	};
}
SNAP.initialize(window.SNAP_CONFIG);