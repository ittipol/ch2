class Setting {

	constructor(csrfToken = null) {
		// Singleton
		if(!Setting.instance){
		  this.csrfToken = csrfToken;
		  Setting.instance = this;
		}

		return Setting.instance;
	}

	static getCsrfToken() {
		return Setting.instance.csrfToken;
	}

}