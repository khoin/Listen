<?php
	header("Access-Control-Allow-Origin: http://www.audiotool.com");
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Listen</title>
	<link rel="icon" href="/Listen/favi.ico" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="description" content="Instant Audiotool auto-brewed playlist for your day.">
	<meta http-equiv="content-language" content="en-gb">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="http://kh01.me/lib/jquery.cookie.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,300,200' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Dosis:200&text=Listen.' rel='stylesheet' type='text/css'>
	<link href="http://kh01.me/lib/grid.bootstrap.min.css" rel="stylesheet" type='text/css'>
	<style>
		::-webkit-scrollbar {  display:none;}
		
		body { font-family: 'Open Sans'; background-color: #282828; color:white;}
		a { color:white; background: rgba(255,255,255,0.2); 
			border-bottom:1px dashed #ff6a00; text-decoration:none;letter-spacing:1px;
			 -moz-transition: .2s all ease; -webkit-transition: .2s all ease; transition: .2s all ease;
			}
		a:hover { background: rgba(255, 106, 0,0.5); text-decoration:none; color:white;}
		h1 { color: #ff6a00; font-weight: 200; font-size: 330%; text-shadow: 1px 1px 15px rgba(255, 106, 0,0.4); }
		
		#liner { font-family:'Dosis',sans-serif; font-size:375%; letter-spacing:1px;}
		
		#authbutt, #logbutt, #playlistbutt { padding: 15px 0;border-radius:30px; background: rgba(255, 106, 0,0.6); cursor: pointer;}
		#login { margin-top: 10px; text-align:center; padding:0}
		.inputwrap{ padding:10px; font-size:150%; border-radius:30px; border: transparent; background: #323232; color:#aaa;}
		input { width:100%; border:0; background: inherit; }
		input[placeholder] { font-weight:200; text-align:center;}
		
		#seeker { height:2px; background: #383838; overflow:hidden; }
		#wow { background: rgba(255, 106, 0,0.8); width: 0%; height: 2px; border-right: 2px white solid;
		-moz-transition: .1s all ease; -webkit-transition: .1s all ease; transition: .1s all ease; 
		}
		.butt {display:inline-block; width:30px; height:30px; border-radius:50%; color: rgba(255,255,255,0.7); line-height:30px;
				background:rgba(255, 106, 0,0.5); font-size:85%; font-weight:200;
				text-align:center; -moz-transition: .3s all ease; -webkit-transition: .3s all ease; transition: .3s all ease; 
				cursor:pointer;
				}
		.butt:hover { background:rgba(255, 106, 0,0.7);}
		.disable { background: rgba(120,120,120,0.7); }
		.disable:hover { background: rgba(120,120,120,0.9); }
		
		.love { -webkit-transform:rotateY(0deg);-moz-transform:rotateY(0deg);transform:rotateY(0deg);
		-webkit-animation:.5s loving infinite linear;  -moz-animation:.5s loving infinite linear;  animation:.5s loving infinite linear; }
		@-webkit-keyframes loving {
		0% {-webkit-transform:rotateY(0deg); } 50% {-webkit-transform:rotateY(180deg); }
		}
		@-moz-keyframes loving {
		0% {-moz-transform:rotateY(0deg); } 50% {-moz-transform:rotateY(180deg); }
		}
		@keyframes loving {
		0% {transform:rotateY(0deg); } 50% {transform:rotateY(180deg); }
		}
	</style>

</head>
<body>
	<div class="container" id="auth" style="text-align:center;">
		<h1 id="liner">Listen</h1>
		<div class="col-md-4 col-md-offset-4 clearfix" id="authbutt" >
			Connect to Audiotool
		</div>
		<div class="col-md-4 col-md-offset-4" id="login" style="display:none;">
			<div class="col-md-5 col-xs-12 inputwrap" style="float:left;margin-bottom:10px">
				<input type="text" id="username" placeholder="username">
			</div>
			<div class="col-md-5 col-md-offset-2 col-xs-12 inputwrap" style="float:right;margin-bottom:10px;">
				<input type="password" id="password" placeholder="password">
			</div><br/>
			<div class="col-md-4 col-md-offset-4 clearfix visible-xs" id="logbutt" style="clear:both;" onclick="fetchAuthentication(true);" >
			Connect
			</div>
			<div style="clear:both;padding-top:10px;">Don't trust me? Run on <a onclick="alert('Still working on this');">Leaf </a></div>
		</div>
	</div>
	<!-- Player down here-->
	<div class="container" id="listen" style="display:none;">
		<div id="seeker">
			<div id="wow">&nbsp;</div>
		</div>
		<div id="controls" style="margin-top:20px;">
			<div>
				<div class="butt" id="pause"><div id="togButt"><b>ll</b></div></div>
				<div class="butt plus" id="info"><b>&nbsp;i&nbsp;</b></div>
				<div class="butt plus disable" id="fav"><div id="love">♥</div></div>
				<div id="notify" style="display:inline-block;border-radius:15px; height:30px; background:rgba(255,10,10,0.6);line-height:30px;font-size:85%;padding: 0 15px;">&nbsp;</div>
				<div class="butt plus hidden-xs" id="helper" onclick="helper();return false;" style="float:right;">?</div>
				<div class="butt plus visible-xs" onclick="player.skip();" style="float:right;margin:0 5px;">»</div> &nbsp;
				<div class="butt plus visible-xs" onclick="logOut();" style="float:right;">&times;</div>
			</div>
			<div class="col-md-4 col-md-offset-4 visible-xs" id="playlistbutt" style="margin-top:20px;background:rgba(10,255,128,0.6);clear:both;text-align:center;" >
				Start Playlist!
			</div>
			<script>
				$("#playlistbutt").off().on('click',function() { player.pause();player.play(); $("#playlistbutt").remove(); } );
			</script>
		</div>

	</div>
	<div class="container" id="end" style="text-align:center;display:none;">
		<h1 id="ender"></h1>
		<p><big>Enjoy your day!</big><br/><br/><br/><br/>
		<small>Alternatively, you can try looking through the code of <a href="http://github.com/potasmic/Listen">this page on GitHub and fork it</a> (not really enjoyable for the day)!</small></p>
	</div>
	<script>
		$("#authbutt").on('click', function() {
			$("#login").slideDown(300);
			$("#authbutt").text("No account? Sign up!").attr("onclick","window.location.href='http://audiotool.com/user/create';").off();
		});
		$("#password").keypress(function(event) {
			if ( event.which == 13 ) {
				event.preventDefault();
				fetchAuthentication(true);
			}
		
		});
		
		function helper() {
		$("#helper").hide();
		var t="";
		t += "----------Hotkeys--------\n";
		t += "\n";
		t += "H	: Show help (this dialog)\n";
		t += "P	: Play / Pause toggle\n";
		t += "Right Arrow	: Skip Current Track\n";
		t += "Left Arrow	: Return to Previous Track\n";
		t += "I	: New Tab with Current Track on Audiotool\n";
		t += "R	: Remix Current Track (if possible)\n";
		t += "X	: Log out\n";
		t += "N	: Refresh (remove fav-ed track from playlist)\n";
		t += "F	: Favorite Current Track\n";
		t += "-------------------------\n";
		t += "(2014) Potasmic. Check GitHub for source.\n Playlist compiled by Audiotool.";
		alert(t);
		
		}
		
		function stitchHotKey() {
			$(document).keydown(function(ev) {
				ev = ev || window.event;
				switch(ev.keyCode || ev.which) {
					case 72: //h
						helper();
					break;	
					case 80: //p
						(player.aud.paused === true)? player.play() : player.pause();
					break;
					case 73: //i
						window.open('http://audiotool.com/track/'+player.list[player.currentTrack]+'/');
					break;
					case 82: //r
						window.open('http://audiotool.com/app/'+player.list[player.currentTrack]+'/');
					break;
					case 39: //right arrow
						ev.preventDefault();
						player.skip();
					break;
					case 37: //left arrow
						ev.preventDefault();
						player.prevTrack();
					break;
					case 88: //x
						logOut();
					break;
					case 78: //n
						document.location.href = '/Listen';
					break;
					case 70: //f
						(checkFavorite(player.list[player.currentTrack]) )? unfavoriteTrack(player.list[player.currentTrack]) : favoriteTrack(player.list[player.currentTrack]) ;
					break;
				}
			})
		};
		
		function logOut() {
			if(confirm("Wanna log out?")) {
					$.removeCookie("listen-cular-session");
					notify("Logged out. Press N and restart.");
				}
		}
		
		function showLogin(a) {
			$("#listen").hide();
			$("#authbutt").text(a);
			$("#auth").show(); $("#login").show();
		}
		
		function notify(str,delay) {
			delay = (typeof delay === 'undefined')? 10000: delay;
			$("#notify").hide().show().text(str).fadeOut(delay);
		}
		
		function checkAuthentication() {
			window.authkey = document.cookie.split( ';' ).map(function ( x ) { return x.trim().split( '=' ); } ).reduce(
				function ( a, b )
				{
					a[ b[ 0 ] ] = b[ 1 ];
					return a;
				}, {} );
			if(window.authkey[ "listen-cular-session"] ) {
				window.authkey = window.authkey["listen-cular-session"];
				return true;	
			} else if(window.authkey["cular-session"] ) { 
				window.authkey = window.authkey["cular-session"];
				return true;
			} else { return false; }
		}
		
		function fetchAuthentication(cont) {
			var usn = $("#username").val();
			var pwd = $("#password").val();
			
			$.ajax("http://api.audiotool.com/users/login/",
			{
				type: "GET",
				data: {
					username: usn,
					password: pwd
				},
				dataType: "xml"
			}).done ( function(xml) {
				if($(xml).find("error").length > 0 ) {
					$("#authbutt").css({animation: ".3s all ease", background:"rgba(255,10,10,0.6)"}).text("Error: "+$(xml).find("error")[0].innerHTML);
				} else{
					$("#authbutt").css({animation: ".3s all ease", background:"rgba(10,255,128,0.6)"}).text("You're ready. Now, listen.");
					$("#login").slideUp();
					var auth = $(xml).find("key")[0].innerHTML;
					$.cookie('listen-cular-session',auth, { expires: 5 } );
					if(cont === true) {
					checkAuthentication();
					nowListen();
					}
				}
				
			}).fail ( function(xhr,msg) {
				alert("Failed authenticating: "+msg);
				
			});
			//If cont is passed, execute nowListen();
			
		
		}
		
		function nowListen() {
			var trackarr = [];
			stitchHotKey();
			$.ajax("http://api.audiotool.com//browse/suggestions/?X-Cular-Session="+window.authkey,
			{
				type: "GET",
				dataType: "xml"
			})
			 .done( function(xml) {
				var dur=0;
				var obj = [];
				$(xml).find("track").each( function(v) {
					dur += parseInt($(this).attr("duration"));
					obj.push($(this).attr("key").toString());
				});
				player.putList(obj);
				player.putDuration(dur);
				$("#auth").slideUp();
				$("#listen").slideDown();
				$("#ender").text("You've listened for "+Math.floor(dur/1000/60)+" minutes and "+Math.round(dur/1000%60)+" seconds.");
			 })
			 .fail( function(xml) {
				if(xml.status === 500) {
					//Key expired. Request new
					$.removeCookie('listen-cular-session');
					$(document).off();
					showLogin("Session expired");
				} else {
					alert("Failed Contacting Audiotool");
				}
			 });
		}
		
		function checkFavorite(key) {
		$("#love").addClass("love");
			var backer;
			$.ajax("http://api.audiotool.com/track/"+key+"/?X-Cular-Session="+window.authkey, {
				type: "GET",
				dataType: "xml",
				async:false
				}).done( function(xml) {
					if( $(xml).find("favorite")[0].innerHTML === "true" ) {
						$("#fav").removeClass("disable").off().on('click',function() { unfavoriteTrack(key); });
						backer = true;
					} else {
						$("#fav").addClass("disable").off().on('click',function() { favoriteTrack(key); });
						backer = false;
					}
				}).always( function() {
					$("#love").removeClass("love");
				});
			return backer;
		}
		
		
		function favoriteTrack(key) {
		$("#love").addClass("love");
			$.ajax("http://api.audiotool.com/track/"+key+"/favorite/?X-Cular-Session="+window.authkey,{type:"get",dataType:"xml"})
			.done( function(xml) {
				if($(xml).find("yey").length > 0) {
					$("#fav").removeClass("disable").off().on('click',function() { unfavoriteTrack(key); });
				} else {
					$("#fav").removeClass("disable").off().on('click',function() { unfavoriteTrack(key); });
					alert("Already favorited this track");
				}
			}).always( function() {
				$("#love").removeClass("love");
			});
		}
		
		function unfavoriteTrack(key) {
		$("#love").addClass("love");
			$.ajax("http://api.audiotool.com/track/"+key+"/unfavorite/?X-Cular-Session="+window.authkey,{type:"get",dataType:"xml"})
			.done( function(xml) {
				if($(xml).find("yey").length > 0) {
					$("#fav").addClass("disable").off().on('click',function() { favoriteTrack(key); });
				} 
			}).always( function() {
				$("#love").removeClass("love");
			});
		}
		
		var Player = (function (arr,dur) {
		//Variables
			this.aud = new Audio();
			this.list = (typeof arr === 'array')? arr: [] ;
			this.currentTrack = 0;
			this.aud.src = "http://api.audiotool.com/track/"+ this.list[this.currentTrack] + "/play." + (this.aud.canPlayType( "audio/ogg" ) ? "ogg" : "mp3") + "?platform=1&ref=listen-app";
			this.aud.load();
			this.duration = dur;
			this.maxTrack = this.list.length;
			window.interval;
			
			var bleh = this;
		//Initial things to do
			$("#pause").on('click',function() { bleh.pause(); } );
			$("#info").off().on('click', function() { window.open('http://audiotool.com/track/'+bleh.list[bleh.currentTrack]+'/'); });
			$("#fav").off().on('click', function() { favoriteTrack(bleh.list[this.currentTrack]); });
			checkFavorite(bleh.list[this.currentTrack]);
		//Functions
			this.play = function() {
				clearInterval(window.interval);
				this.aud.pause();
				this.aud.play();
				window.interval = setInterval(bleh.update, 100);
				$("#pause").removeClass("disable").on('click',function() { bleh.pause(); } ).html("<div id='togButt'><b>ll</b></div>");
	
			}
			
			this.aud.addEventListener('waiting', function() { $("#togButt").addClass("love"); }, false);
			this.aud.addEventListener('playing', function() { $("#togButt").removeClass("love"); }, false);
			this.aud.addEventListener('error',function () { notify("Error loading audio"); },false);
			
			this.update = function() {
				$("#wow")[0].style.width = bleh.aud.currentTime/bleh.aud.duration*100 +"%";
				if(bleh.aud.currentTime === bleh.aud.duration && bleh.aud.currentTime > 10) {
					bleh.nextTrack();
				}
			}
			
			this.pause = function() {
				this.aud.pause();
				clearInterval(window.interval);
				$("#pause").addClass("disable").off().on('click',function() { bleh.play(); }).html("<div id='togButt'>&#9654;</div>");	
			}
			
			this.nextTrack = function() {
				clearInterval(window.interval);
				if(this.currentTrack+1 >= this.list.length ) {
					clearInterval(window.interval);
					finishedListen();
				} else {
					this.aud.src = "http://api.audiotool.com/track/"+ this.list[this.currentTrack+1] + "/play." + (this.aud.canPlayType( "audio/ogg" ) ? "ogg" : "mp3") + "?platform=1&ref=listen-app";
					this.aud.load();
					this.currentTrack += 1;
					$("#info").off().on('click', function() { window.open('http://audiotool.com/track/'+bleh.list[bleh.currentTrack]+'/'); });
					$("#fav").off().on('click', function() { favoriteTrack(bleh.list[bleh.currentTrack]); });
					checkFavorite(bleh.list[bleh.currentTrack]);
					this.play();
					notify("Track "+(bleh.currentTrack+1)+" of 25");
				}
			}

			this.prevTrack = function() {
				clearInterval(window.interval);
				this.aud.src = "http://api.audiotool.com/track/"+ this.list[this.currentTrack-1] + "/play." + (this.aud.canPlayType( "audio/ogg" ) ? "ogg" : "mp3") + "?platform=1&ref=listen-app";
					this.aud.load();
					this.currentTrack -= 1;
					$("#info").off().on('click', function() { window.open('http://audiotool.com/track/'+bleh.list[bleh.currentTrack]+'/'); });
					$("#fav").off().on('click', function() { favoriteTrack(bleh.list[bleh.currentTrack]); });
					checkFavorite(bleh.list[bleh.currentTrack]);
					this.play();
					notify("Track "+(bleh.currentTrack+1)+" of 25");
			}
			
			this.getList = function() {
				return this.list;
			}
			
			this.skip = function() {
				bleh.aud.currentTime = bleh.aud.duration;
				notify("Track "+(bleh.currentTrack+2)+" of 25");
			}
			
			this.putList = function(arry) {
	
				this.list = arry;
				this.currentTrack = 0;
				bleh.aud.src = "http://api.audiotool.com/track/"+ bleh.list[0] + "/play." + (bleh.aud.canPlayType( "audio/ogg" ) ? "ogg" : "mp3") + "?platform=1&ref=listen-app";
				bleh.aud.load();
				bleh.play();
				checkFavorite(bleh.list[bleh.currentTrack]);
			}
			
			this.putDuration = function (i) {
				this.duration = i;
			}
			
			this.exploit = function() {
				alert("Tracks are: \n** "+this.list.join("\n** "));
			}
			
		});
		
		function finishedListen() {
			player.pause();
			clearInterval(window.interval);
			console.log("Done");
			$("#listen").slideUp();
			$("#end").slideDown();
		
		}
		
	</script>
	<script>
	$(document).ready( function() {
		window.player = new Player([],0);
		
		if(checkAuthentication()) {
			nowListen();
			$("#authbutt").css({animation: ".3s all ease", background:"rgba(10,255,128,0.6)"}).text("Already logged in.");
		}
		//For #auth
		var auth = $("#auth");
		var listen = $("#listen");
		var end = $("#end");
		
		var docH = $(document).height();
		auth.height( docH );
		end.height( docH );
		listen.height( docH - (docH/2)-1 );
		
		$("#liner, #ender").css( {paddingTop: docH/5} )
		$("#liner").hide().fadeIn(1000);
		$("#authbutt").hide().delay(700).fadeIn(600); 
		$("#login").hide();
		//For #listen
		$("#seeker").css( {marginTop: (docH/2)-1 } );
		
		$("#notify").hide();
		
	});
	</script>
</body>
</html>