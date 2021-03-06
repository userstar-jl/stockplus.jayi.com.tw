$(document).ready(function () {
    $("#line_login").click(function() {
    	var response_type = "code";
    	var client_id = "1653732060";
    	var redirect_uri = "http://pgm-dev.us-west-2.elasticbeanstalk.com/user/line_auth";
    	var state = "12345abcde";
    	// A unique alphanumeric string used to prevent cross-site request forgery. 
    	// This value should be randomly generated by your application. 
    	// Cannot be a URL-encoded string.
    	var scope = "openid%20profile%20email";
    	var authorize_api = "https://access.line.me/oauth2/v2.1/authorize?response_type=code"
    								+ "&client_id=" + client_id
    								+ "&redirect_uri=" + redirect_uri
    								+ "&state=" + state 
    								+ "&scope=" + scope;
    	$("#line_login").attr("href", authorize_api)
    });
    
    var provider;
    $("#google_login").click(function() {
    	provider = new firebase.auth.GoogleAuthProvider();
    	firebaseLogin();
    });
    
    $("#facebook_login").click(function() {
    	provider = new firebase.auth.FacebookAuthProvider();
    	firebaseLogin();
    });
    
    function firebaseLogin() {
//    	firebase.auth().signOut().then(function() {
//		  // Sign-out successful.
//		}).catch(function(error) {
//		  // An error happened.
//		});
		firebase.auth().signInWithPopup(provider).then(function(result) {
			// This gives you a Facebook/Google Access Token. You can use it to access the Official API.
			var token = result.credential.accessToken;
			// The signed-in user info.
			var user = firebase.auth().currentUser;
			var name, email, photoUrl, uid, emailVerified;
	
			if (user != null) {
				name = user.displayName;
				email = user.email;
				photoUrl = user.photoURL;
				emailVerified = user.emailVerified;
				uid = user.uid;
				alert("Signed in: " + uid
						+ "\nFull Name: " + name
						// + "\nGiven Name: " + profile.getGivenName()
						// + "\nFamily Name: " + profile.getFamilyName()
						+ "\nImage URL: " + photoUrl
						+ "\nEmail: " + email);
			}}).catch(function(error) {
				// Handle Errors here.
				var errorCode = error.code;
				var errorMessage = error.message;
				// The email of the user's account used.
				var email = error.email;
				// The firebase.auth.AuthCredential type that was used.
				var credential = error.credential;
				// ...
			});
    }
});

