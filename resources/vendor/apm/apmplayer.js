if (typeof APMPlayerFactory === "undefined") {
    var APMPlayerFactory = function() {
        "use strict";
        var e = {
            type: {
                AUDIO: "audio",
                LIVE_AUDIO: "live_audio"
            },
            isValid: function(t) {
                var n;
                for (n in e.type) {
                    if (e.type[n] === t) {
                        return true
                    }
                }
                return false
            }
        };
        var t = function() {};
        t.enabled = false;
        t.consoleOnly = true;
        t.log = function(e, n, r) {
            if (t.enabled === false) {
                return
            }
            if (typeof soundManager !== "undefined") {
                if (typeof r === "undefined") {
                    r = "APMPlayer"
                }
                soundManager._writeDebug(r + "::" + e, n.id, false)
            } else {
                console.log(r + "::" + e + "[" + n.name + "]")
            }
        };
        t.type = {
            info: {
                id: 1,
                name: "info"
            },
            warn: {
                id: 2,
                name: "warning"
            },
            error: {
                id: 3,
                name: "error"
            }
        };
        var n = function() {
            this.type = {
                AUDIO_LIB_READY: "AUDIO_LIB_READY",
                MEDIA_READY: "MEDIA_READY",
                PLAYER_READY: "PLAYER_READY",
                PLAYER_FAILURE: "PLAYER_FAILURE",
                CONNECTION_LOST: "CONNECTION_LOST",
                MISSING_FILE: "MISSING_FILE",
                PLAYLIST_CURRENT_CHANGE: "PLAYLIST_CURRENT_CHANGE",
                POSITION_UPDATE: "POSITION_UPDATE",
                PLAYING: "PLAYING",
                PAUSED: "PAUSED",
                FINISHED: "FINISHED",
                UNLOADED: "UNLOADED",
                BUFFER_START: "BUFFER_START",
                BUFFER_END: "BUFFER_END",
                METADATA: "METADATA",
                VOLUME_UPDATED: "VOLUME_UPDATED"
            };
            this.handlers = []
        };
        n.prototype = {
            trigger: function(e, t) {
                var n;
                for (n = 0; n < this.handlers.length; n += 1) {
                    if (this.handlers[n].eventName === e) {
                        this.handlers[n].eventHandler.call(this, t)
                    }
                }
            },
            addListener: function(e, n) {
                if (typeof e !== "string" || typeof n !== "function") {
                    t.log("Invalid parameters when creating listener with the following arguments: 'Name': " + e + ", 'Handler': " + n, t.type.error)
                }
                this.handlers.push({
                    eventName: e,
                    eventHandler: n
                })
            },
            removeListeners: function() {
                this.handlers = []
            }
        };
        var r = function() {
            this.type = {
                PLAYING: "PLAYING",
                STOPPED: "STOPPED",
                PAUSED: "PAUSED"
            };
            this._current = this.type.STOPPED
        };
        r.prototype = {
            current: function() {
                return this._current
            },
            set: function(e, t) {
                this._current = e;
                if (typeof t === "object" && t.hasOwnProperty("state")) {
                    t.state = this._current
                }
            }
        };
        var i = function() {
            this.type = {
                FLASH: "FLASH",
                HTML5: "HTML5"
            };
            this.solutions = [this.type.FLASH, this.type.HTML5]
        };
        i.prototype = {
            getCurrentSolution: function() {
                if (this.solutions.length > 0) {
                    return this.solutions[0]
                }
                return null
            },
            removeCurrentSolution: function() {
                if (this.solutions.length > 0) {
                    this.solutions.shift();
                    return true
                }
                t.log("PlaybackMechanism.removeCurrentSolution() no playback solutions remain to remove!", t.type.error);
                return false
            },
            setSolutions: function(e) {
                if (e instanceof Array) {
                    var n = [];
                    var r;
                    while (e.length > 0) {
                        r = e.shift();
                        if (this.isValid(r)) {
                            n.push(r)
                        } else {
                            t.log("PlaybackMechanism.setSolutions() passed mechanism '" + r + "' is invalid.", t.type.error)
                        }
                    }
                    this.solutions = n;
                    return true
                }
                t.log("PlaybackMechanism.setSolutions() argument passed is not an array!", t.type.error);
                return false
            },
            isValid: function(e) {
                var t;
                for (t in this.type) {
                    if (this.type[t] === e) {
                        return true
                    }
                }
                return false
            }
        };
        var s = function() {
            this.schemes = [];
            this.scheme_map = {};
            this.playable_attrs = []
        };
        s.prototype = {
            init: function(e) {
                this.scheme_map = e;
                this.initSchemeTypes();
                this.initPlayableAttrs()
            },
            initSchemeTypes: function() {
                this.schemes = [];
                for (var e in this.scheme_map) {
                    this.schemes.push(e)
                }
            },
            initPlayableAttrs: function() {
                this.playable_attrs = [];
                var e = new u({});
                for (var t in e) {
                    if (typeof e[t] !== "function") {
                        this.playable_attrs.push(t)
                    }
                }
            },
            hasSchemes: function() {
                if (this.schemes.length > 0) {
                    return true
                }
                return false
            },
            isValid: function(e) {
                if (this.schemes.indexOf(e) !== -1) {
                    return true
                }
                return false
            },
            isScheme: function(e, t) {
                var n = this.parse(e);
                if (n !== null && n.scheme === t) {
                    return true
                }
                return false
            },
            parse: function(e) {
                var t = "^(" + this.schemes.join("|") + "){1}(:/){1}([/\\w .-]+$)";
                var n = new RegExp(t);
                var r = e.match(n);
                if (r !== null && r.length === 4) {
                    return {
                        scheme: r[1],
                        path: r[3]
                    }
                }
                return null
            },
            getValues: function(e) {
                var t = {};
                var n = this.parse(e);
                if (n !== null && this.isValid(n.scheme)) {
                    var r = this.scheme_map[n.scheme];
                    if (r.hasOwnProperty(n.path)) {
                        r = r[n.path]
                    }
                    for (var i in r) {
                        if (this.playable_attrs.indexOf(i) !== -1) {
                            t[i] = r[i]
                        } else if (i === "flash_file_prefix") {
                            t.flash_file_path = r[i] + "/" + n.path
                        } else if (i === "http_file_prefix") {
                            t.http_file_path = r[i] + "/" + n.path
                        }
                    }
                }
                return t
            }
        };
        var o = new s;
        var u = function(e) {
            var t = this;
            this.identifier = null;
            this.type = null;
            this.flash_file_path = "";
            this.flash_server_url = "";
            this.http_file_path = "";
            this.buffer_time = 3;
            this.downloadable = true;
            this.title = "";
            this.description = "";
            this.artist = "";
            this.list_id = "";
            this.external_url = "";
            this.url = "";
            this.detail = "";
            this.date = "";
            this.program = "";
            this.host = "";
            this.image_sm = "";
            this.image_lg = "";
            this.duration = 0;
            this.position = 0;
            this.percent_played = 0;
            this.percent_loaded = 0;
            this.time_played = 0;
            this.state = "STOPPED";
            if (typeof e.identifier === "undefined" || e.identifier === null || e.identifier === "") {
                return t
            }(function() {
                t.setMembers(e);
                if (o.hasSchemes()) {
                    var n = o.getValues(t.identifier);
                    t.setMembers(n)
                }
            })()
        };
        u.prototype = {
            isValid: function() {
                if (this.identifier !== null && this.type !== null && e.isValid(this.type)) {
                    return true
                }
                return false
            },
            isCustomScheme: function(e) {
                if (o.isScheme(this.identifier, e) === true) {
                    return true
                }
                return false
            },
            isEOF: function() {
                if (this.percent_played > .99995) {
                    return true
                }
                return false
            },
            reset: function() {
                this.position = 0;
                this.percent_played = 0;
                this.percent_loaded = 0
            },
            setEmptyMembers: function(e) {
                var t;
                for (t in e) {
                    if (e.hasOwnProperty(t) && this.hasOwnProperty(t) && e[t] !== null && e[t] !== "" && (this[t] === null || this[t] === "" || this[t] === 0)) {
                        this[t] = e[t]
                    }
                }
            },
            setMembers: function(e) {
                for (var t in e) {
                    if (e.hasOwnProperty(t) && this.hasOwnProperty(t)) {
                        this[t] = e[t]
                    }
                }
            },
            clearFlashProperties: function() {
                this.flash_server_url = "";
                this.flash_file_path = ""
            },
            isFlashStreamable: function() {
                if (this.flash_server_url !== "" && this.flash_file_path !== "") {
                    return true
                }
                return false
            }
        };
        var a = function() {
            var s = this;
            var o = function() {
                this.lib = soundManager;
                this.init_status = false
            };
            o.prototype = {
                init: function() {
                    if (s.audio.init_status === false) {
                        this.lib.flashVersion = 9;
                        this.lib.preferFlash = true;
                        this.lib.useHTML5Audio = true;
                        this.lib.consoleOnly = t.consoleOnly;
                        this.lib.debugMode = t.enabled;
                        this.lib.flashPollingInterval = 150;
                        this.lib.url = s.util.getLoadedScriptPathByFileName("soundmanager2") + "swf/";
                        this.lib.onready(function() {
                            if (s.audio.lib.html5Only === true) {
                                s.mechanism.setSolutions([s.mechanism.type.HTML5]);
                                t.log("Audio.init() -- setting to HTML5-only", t.type.info)
                            }
                            t.log("Audio.init() success", t.type.info);
                            s.audio.init_status = true;
                            s.internal_events.trigger(s.events.type.AUDIO_LIB_READY, {})
                        });
                        this.lib.ontimeout(function(e) {
                            if (!s.audio.lib.canPlayMIME("audio/mp3") && !s.audio.lib.canPlayMIME("audio/mpeg")) {
                                s.mechanism.setSolutions([])
                            } else {
                                s.mechanism.setSolutions([s.mechanism.type.HTML5])
                            }
                            s.audio.reset()
                        })
                    } else {
                        s.audio.reset();
                        t.log("Audio.init() -- audio lib has already been initialized once, attempting reset", t.type.info)
                    }
                },
                reset: function() {
                    s.audio.init_status = false;
                    var e = s.mechanism.getCurrentSolution();
                    switch (e) {
                        case s.mechanism.type.FLASH:
                            s.audio.lib.preferFlash = true;
                            s.audio.lib.html5Only = false;
                            break;
                        case s.mechanism.type.HTML5:
                            s.audio.lib.preferFlash = false;
                            s.audio.lib.html5Only = true;
                            break;
                        default:
                            t.log("Audio.reset() no playback solution exists.", t.type.error);
                            s.events.trigger(s.events.type.PLAYER_FAILURE, null);
                            return false
                    }
                    s.audio.lib.reboot()
                },
                load: function(e) {
                    if (this.init_status === true) {
                        try {
                            var n;
                            switch (s.mechanism.getCurrentSolution()) {
                                case s.mechanism.type.FLASH:
                                    n = {
                                        id: e.identifier,
                                        url: e.http_file_path,
                                        bufferTime: e.buffer_time,
                                        onconnect: function() {
                                            t.log("Audio.load.lib.createSound.onConnect() - successfully connected over RTMP (" + e.flash_server_url + ")", t.type.info);
                                            s.internal_events.trigger(s.events.type.MEDIA_READY, e)
                                        },
                                        onfailure: function(e) {
                                            var n = s.current_playable;
                                            if (n.position > 0) {
                                                t.log("Audio.load.createSound.onfailure() -- network connection has been lost", t.type.info);
                                                s.state.set(s.state.type.STOPPED, n);
                                                s.events.trigger(s.events.type.CONNECTION_LOST, n)
                                            } else if (e.connected === true) {
                                                t.log("Audio.load.createSound.onfailure() - requested file '" + n.flash_file_path + "' w/ identifier '" + n.identifier + "' could not be found in flash/RTMP mode.", t.type.error);
                                                s.state.set(s.state.type.STOPPED, n);
                                                s.events.trigger(s.events.type.MISSING_FILE, n);
                                                s.events.trigger(s.events.type.FINISHED, n)
                                            } else {
                                                t.log("Audio.load.createSound.onfailure() - could not connect to '" + n.flash_server_url + "' ... falling back to HTML5-mode.", t.type.error);
                                                s.state.set(s.state.type.STOPPED, n);
                                                s.mechanism.removeCurrentSolution();
                                                s.audio.reset()
                                            }
                                        },
                                        onload: function(e) {
                                            if (e === false) {
                                                var n = s.current_playable;
                                                s.events.trigger(s.events.type.MISSING_FILE, n);
                                                t.log("Audio.load.createSound.onload() - could not load '" + n.http_file_path + "' over progressive download", t.type.error)
                                            }
                                        }
                                    };
                                    if (e.flash_file_path !== null && e.flash_file_path !== "" && e.flash_server_url !== null && e.flash_server_url !== "") {
                                        n.serverURL = e.flash_server_url;
                                        n.url = e.flash_file_path
                                    }
                                    this.lib.createSound(n);
                                    break;
                                case s.mechanism.type.HTML5:
                                    n = this.lib.createSound({
                                        id: e.identifier,
                                        url: e.http_file_path,
                                        onload: function(e) {
                                            var n = s.current_playable;
                                            if (!e) {
                                                s.state.set(s.state.type.STOPPED, n);
                                                s.events.trigger(s.events.type.MISSING_FILE, n);
                                                s.events.trigger(s.events.type.FINISHED, n);
                                                t.log("Audio.load.createSound.onload():  requested file '" + n.http_file_path + "' w/ identifier '" + n.identifier + "' could not be found in HTML5 mode.", t.type.error)
                                            } else {
                                                if (this.duration) {
                                                    n.duration = this.duration;
                                                    t.log("Audio.load.createSound.onload(): duration found after start", t.type.info)
                                                } else {
                                                    t.log("Audio.load.createSound.onload(): duration unknown", t.type.info)
                                                }
                                            }
                                        }
                                    });
                                    s.internal_events.trigger(s.events.type.MEDIA_READY, e);
                                    break;
                                default:
                                    t.log("Audio.load() no playback solution exists.", t.type.error);
                                    s.events.trigger(s.events.type.PLAYER_FAILURE, e);
                                    break
                            }
                        } catch (r) {
                            t.log("Exception thrown in APMPlayer.Audio.load : " + r.toString(), t.type.error)
                        }
                    } else {
                        if (s.mechanism.getCurrentSolution() === null) {
                            s.events.trigger(s.events.type.PLAYER_FAILURE, e)
                        } else {
                            t.log("Audio.lib.load - audio lib not initialized.  load() will be called again when player is finally initialized.", t.type.info)
                        }
                    }
                },
                unload: function(e) {
                    if (s.current_playable !== null) {
                        t.log("Audio.unload() about to stop, drop and roll current sound.", t.type.info);
                        this.lib.destroySound(e.identifier);
                        e.reset();
                        s.state.set(s.state.type.STOPPED, e);
                        s.events.trigger(s.events.type.UNLOADED, e)
                    }
                },
                play: function(n) {

                    if ($('#please-vote-msg').length ) {
                        $('#please-vote-msg').show();
                    };

                    if (!this.lib.getSoundById(n.identifier)) {
                        this.load(n)
                    } else {
                        t.log("Audio.play() attempting to play from lib.", t.type.info);
                        this.lib.play(n.identifier, {
                            volume: s.settings.volume * 100,
                            position: n.position,
                            onplay: function() { 
                                s.state.set(s.state.type.PLAYING, n);
                                s.events.trigger(s.events.type.PLAYING, n);
                                s.events.trigger(s.events.type.METADATA, n);
                                if (s.settings.muted) {
                                    this.mute()
                                }
                                t.log("Audio.play.onplay() PLAYING fired", t.type.info)
                                
                //add active class to playlist LI, not working properly at the moment - steve           
               //     $('#apm_playlist').find("li").removeClass('playlist-active-li');
               //     $('#apm_playlist li[ id = \'' + n.position + '\']').addClass('playlist-active-li');

                            },
                            onpause: function() {                                
                                s.state.set(s.state.type.PAUSED, n);
                                s.events.trigger(s.events.type.PAUSED, n);
                                t.log("Audio.play.onpause() PAUSED fired", t.type.info)
                            },
                            onresume: function() {
                                s.state.set(s.state.type.PLAYING, n);
                                s.events.trigger(s.events.type.PLAYING, n);
                                t.log("Audio.play.onresume() PLAYING fired", t.type.info)
                            },
                            onfinish: function() {
                                console.log(n)

                                s.current_playable.reset();
                                s.state.set(s.state.type.STOPPED, n);
                                s.events.trigger(s.events.type.FINISHED, n);
                                t.log("Audio.play.onfinish() FINISHED fired; playable reset.", t.type.info)
                           
                           // $.ajax({
                           //     data: {type: 'sp', m: 'SOME OTHER TOKEN', token: 'TOKEN-HERE'},
                           //     type: 'POST',
                           //     url: 'http://hhvip.dev/log/event',
                           //          success: function(result) {
                           //          }
                           //      });


                            },
                            onbufferchange: function() {
                                if (this.isBuffering === true) {
                                    s.events.trigger(s.events.type.BUFFER_START, n);
                                    t.log("Audio.play.onbufferchange() BUFFER_START fired ", t.type.info)
                                } else {
                                    s.events.trigger(s.events.type.BUFFER_END, n);
                                    t.log("Audio.play.onbufferchange() BUFFER_END fired ", t.type.info)
                                }
                            },
                            whileplaying: function() {
                                var t = s.current_playable;
                                if (t.type === e.type.LIVE_AUDIO) {
                                    t.percent_played = 1;
                                    t.duration = 0;
                                    t.position = this.position;
                                    s.events.trigger(s.events.type.POSITION_UPDATE, t)
                                } else {
                                    if (this.position !== 0) {
                                        t.position = this.position
                                    }
                                    if (this.duration !== 0 && this.duration > t.duration) {
                                        t.duration = this.duration
                                    }
                                    if (this.durationEstimate > this.duration) {
                                        t.percent_loaded = this.duration / this.durationEstimate
                                    } else if (t.percent_loaded > 0 && t.percent_loaded < 1) {
                                        t.percent_loaded = 1
                                    }
                                    if (t.duration > 0) {
                                        t.percent_played = t.position / t.duration
                                    }
                                    if (t.isEOF()) {
                                        t.percent_played = 1;
                                        t.position = t.duration
                                    } else {
                                        s.events.trigger(s.events.type.POSITION_UPDATE, t)
                                    }
                                }
                            },
                            onmetadata: function() {

                                if (this.hasOwnProperty("metadata")) {
                                    if (this.metadata.hasOwnProperty("adw_ad") && this.metadata.adw_ad === "true" && this.metadata.hasOwnProperty("metadata") && this.metadata.metadata.indexOf("adswizzContext") !== -1) {
                                        t.log("onmetadata() received adw_ad of insertionType: '" + this.metadata.insertionType + "'", t.type.info);
                                        n.title = "adw_ad_" + this.metadata.insertionType;
                                        n.adw_context = this.metadata.metadata.substr(15);
                                        s.events.trigger(s.events.type.METADATA, n)
                                    } else if (this.metadata.hasOwnProperty("StreamTitle") && typeof this.metadata.StreamTitle !== "undefined") {
                                        t.log("onmetadata() received metadata w/ title: '" + this.metadata.StreamTitle + "'", t.type.info);
                                        n.title = this.metadata.StreamTitle;
                                        s.events.trigger(s.events.type.METADATA, n)
                                    }
                                }
                            }
                        })
                    }
                },
                pause: function(e) {
                    var n = this.lib.getSoundById(e.identifier);
                    if (n) {
                        n.pause();
                        return true
                    }
                    t.log("Audio.pause() Error.  Could not pause.  '" + e.identifier + "' is unknown.", t.type.warn);
                    return false
                },
                unpause: function(e) {
                    var n = this.lib.getSoundById(e.identifier);
                    if (n && n.paused === true) {
                        n.resume();
                        return true
                    }
                    t.log("Audio.unpause() Error.  Could not unpause.  '" + e.identifier + "' is unknown.", t.type.warn);
                    return false
                },
                seek: function(e, n) {
                    var r = this.lib.getSoundById(e.identifier);
                    if (r) {
                        if (e.duration) {
                            t.log("Audio.seek() seeking to '" + n + "' of sound '" + e.identifier + "'", t.type.info);
                            var i = n * e.duration;
                            r.setPosition(i);
                            return true
                        }
                        t.log("Audio.seek() Error.  Could not seek. duration of '" + e.identifier + "' is unknown.", t.type.warn);
                        return false
                    }
                    t.log("Audio.seek() sound '" + e.identifier + "' is unknown.", t.type.warn);
                    return false
                },
                mute: function(e) {
                    var t = this.lib.getSoundById(e.identifier);
                    if (t) {
                        t.mute()
                    }
                },
                unmute: function(e) {
                    var t = this.lib.getSoundById(e.identifier);
                    if (t) {
                        t.unmute()
                    }
                },
                setVolume: function(e, n) {
                    var r = n * 100;
                    var i = this.lib.getSoundById(e.identifier);
                    if (i) {
                        t.log("Audio.setVolume() setting volume to " + r + "% (out of 100)", t.type.info);
                        i.setVolume(r)
                    } else {
                        t.log("Audio.setVolume() sound is not loaded.  volume will be set to " + r + "% once audio begins playing", t.type.info)
                    }
                }
            };
            this.settings = {
                volume: .9,
                muted: false,
                debug: false
            };
            this.current_playable = null;
            this.events = new n;
            this.internal_events = new n;
            this.mechanism = new i;
            this.state = new r;
            this.audio = new o;
            this.internal_event_handlers = {
                checkReady: function() {
                    if (s.audio.init_status === true) {
                        t.log("checkReady() player ready.  all dependencies loaded.", t.type.info);
                        s.events.trigger(s.events.type.PLAYER_READY, {})
                    } else {
                        t.log("checkReady() not quite ready -- waiting for other dependencies to load...", t.type.info)
                    }
                },
                onMediaReady: function(e) {
                    s.audio.play(e)
                }
            };
            this.internal_events.addListener(s.events.type.AUDIO_LIB_READY, s.internal_event_handlers.checkReady);
            this.internal_events.addListener(s.events.type.MEDIA_READY, s.internal_event_handlers.onMediaReady);
            this.util = {
                getParameterByName: function(e) {
                    e = e.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                    var t = "[\\?&]" + e + "=([^&#]*)";
                    var n = new RegExp(t);
                    var r = n.exec(window.location.href);
                    if (r === null) {
                        return ""
                    }
                    return decodeURIComponent(r[1].replace(/\+/g, " "))
                },
                getLoadedScriptPathByFileName: function(e) {
                    var t = document.getElementsByTagName("script");
                    var n = t.length;
                    var r;
                    for (r = 0; r < n; r += 1) {
                        var i = t[r].src.indexOf(e, 0);
                        if (i !== -1) {
                            return t[r].src.slice(0, i)
                        }
                    }
                },
                getProjectBasePath: function() {
                    var e = s.util.getLoadedScriptPathByFileName("script/apmplayer-all.min.js");
                    if (typeof e === "undefined") {
                        e = s.util.getLoadedScriptPathByFileName("script/apmplayer.js")
                    }
                    return e
                },
                mergeSettings: function(e) {
                    var t;
                    for (t in e) {
                        if (e.hasOwnProperty(t) && s.settings.hasOwnProperty(t)) {
                            s.settings[t] = e[t]
                        }
                    }
                },
                checkDebug: function() {
                    if (s.util.getParameterByName("debug")) {
                        t.enabled = true
                    }
                    if (s.util.getParameterByName("debug") === "all") {
                        t.consoleOnly = false
                    }
                },
                addIEFixes: function() {
                    if (!Array.prototype.indexOf) {
                        Array.prototype.indexOf = function(e) {
                            var t = this.length >>> 0;
                            var n = Number(arguments[1]) || 0;
                            n = n < 0 ? Math.ceil(n) : Math.floor(n);
                            if (n < 0) n += t;
                            for (; n < t; n++) {
                                if (n in this && this[n] === e) return n
                            }
                            return -1
                        }
                    }
                }
            };
            return {
                init: function() {
                    s.util.addIEFixes();
                    s.util.checkDebug();
                    s.audio.init()
                },
                reset: function(e) {
                    if (e instanceof Array) {
                        s.mechanism.setSolutions(e)
                    }
                    s.audio.reset()
                },
                play: function(n, r) {

                    if (n instanceof u) {
                        if (n === s.current_playable && s.state.current() !== s.state.type.PLAYING) {
                            switch (n.type) {
                                case e.type.AUDIO:
                                case e.type.LIVE_AUDIO:
                                    s.audio.play(n);
                                    break;
                                case e.type.VIDEO:
                                    break;
                                default:
                                    t.log("play() unsupported type: '" + n.type + "'", t.type.error)
                            }
                        } else if (n !== s.current_playable) {
                            if (s.mechanism.getCurrentSolution() === null) {
                                t.log("play()  insufficient playback mechanism for platform.  Triggered PLAYER_FAILURE", t.type.error);
                                s.events.trigger(s.events.type.PLAYER_FAILURE, n);
                                return false
                            }
                            if (s.current_playable !== null) {
                                s.audio.unload(s.current_playable)
                            }
                            s.util.mergeSettings(r);
                            s.current_playable = n;
                            switch (n.type) {
                                case e.type.AUDIO:
                                case e.type.LIVE_AUDIO:
                                    s.audio.load(n);
                                    break;
                                case e.type.VIDEO:
                                    break;
                                default:
                                    t.log("load() unsupported type: '" + n.type + "'", t.type.error);
                                    break
                            }
                        }
                    } else {
                        t.log("play() invalid playable passed.  must of of type Playable.  did nothing.", t.type.error);
                        return false
                    }
                },
                pause: function() {
                    var n = s.current_playable;
                    if (n !== null) {
                        switch (n.type) {
                            case e.type.AUDIO:
                                s.audio.pause(n);
                                break;
                            case e.type.LIVE_AUDIO:
                                s.audio.unload(n);
                                break;
                            case e.type.VIDEO:
                                break;
                            default:
                                t.log("pause() unsupported type: '" + n.type + "'", t.type.error)
                        }
                    } else {
                        t.log("pause() no current playable loaded.  nothing to pause.", t.type.warn)
                    }
                },
                unload: function() {
                    var n = s.current_playable;
                    if (n !== null) {
                        switch (n.type) {
                            case e.type.AUDIO:
                            case e.type.LIVE_AUDIO:
                                s.audio.unload(n);
                                break;
                            case e.type.VIDEO:
                                break;
                            default:
                                t.log("unload() unsupported type: '" + n.type + "'", t.type.error)
                        }
                    } else {
                        t.log("unload() no current playable loaded.  nothing to stop/unload.", t.type.info)
                    }
                },
                seek: function(n) {
                    var r = s.current_playable;
                    if (r !== null) {
                        switch (r.type) {
                            case e.type.AUDIO:
                                s.audio.seek(r, n);
                                break;
                            case e.type.LIVE_AUDIO:
                                t.log("seek() sorry, this item is not seekable '" + r.identifier + "', type: '" + r.type + "'", t.type.info);
                                break;
                            case e.type.VIDEO:
                                break;
                            default:
                                t.log("seek() unsupported type: '" + r.type + "'", t.type.error)
                        }
                    } else {
                        t.log("seek() no current playable loaded.  nothing to seek.", t.type.info)
                    }
                },
                mute: function() {
                    s.settings.muted = true;
                    var n = s.current_playable;
                    if (n !== null) {
                        switch (n.type) {
                            case e.type.AUDIO:
                            case e.type.LIVE_AUDIO:
                                s.audio.mute(n);
                                break;
                            case e.type.VIDEO:
                                break;
                            default:
                                t.log("mute() unsupported type: '" + n.type + "'", t.type.error);
                                break
                        }
                    }
                    t.log("mute() -- player is now muted.", t.type.info)
                },
                unmute: function() {
                    s.settings.muted = false;
                    var n = s.current_playable;
                    if (n !== null) {
                        switch (n.type) {
                            case e.type.AUDIO:
                            case e.type.LIVE_AUDIO:
                                s.audio.unmute(n);
                                break;
                            case e.type.VIDEO:
                                break;
                            default:
                                t.log("unmute() unsupported type: '" + n.type + "'", t.type.error);
                                break
                        }
                    }
                    t.log("unmute() -- player is now unmuted.", t.type.info)
                },
                setVolume: function(n) {
                    if (n < 0) {
                        n = 0;
                        t.log("setVolume() invalid percent_decimal passed: '" + n + "' is less than 0.  percent_decimal set to 0.  percentages must be represented as a decimal from 0 to 1 (eg .45)", t.type.warn)
                    } else if (n > 1) {
                        n = 1;
                        t.log("setVolume() invalid percent_decimal passed: '" + n + "' is greater than 1.  percent_decimal set to 1.00 by default.  percentages must be represented as a decimal from 0.00 to 1.00 (eg .45)", t.type.warn)
                    }
                    var r = s.current_playable;
                    if (r !== null) {
                        switch (r.type) {
                            case e.type.AUDIO:
                            case e.type.LIVE_AUDIO:
                                s.audio.setVolume(r, n);
                                break;
                            case e.type.VIDEO:
                                break;
                            default:
                                t.log("setVolume() unsupported type: '" + r.type + "'", t.type.error)
                        }
                    } else {
                        t.log("setVolume() no playable loaded.  VOLUME_UPDATED event still fired. new vox : '" + n + "'", t.type.info)
                    }
                    s.settings.volume = n;
                    s.events.trigger(s.events.type.VOLUME_UPDATED, {
                        percent_decimal: n
                    })
                },
                debug: t,
                events: s.events,
                mechanism: s.mechanism,
                mediaTypes: e.type,
                state: s.state,
                base_path: s.util.getProjectBasePath()
            }
        };
        var f = function() {
            this.events = new n;
            this._items = [];
            this._current_index = null
        };
        f.prototype = {
            add: function(e) {
                if (e instanceof u && e.isValid()) {
                    if (this.item(e.identifier) !== null) {
                        t.log("add() could not add '" + e.identifier + "' to playlist because it already exists!", t.type.warn, "Playlist");
                        return false
                    }
                    this._items.push(e);
                    if (this._current_index === null) {
                        this._current_index = 0;
                        this.events.trigger(this.events.type.PLAYLIST_CURRENT_CHANGE, null)
                    }
                    t.log("add() new playable successfully added to playlist: '" + e.identifier + "'", t.type.info, "Playlist");
                    return true
                }
                t.log("add() -- error: nothing added to playlist.  either object passed was not of type Playable or identifier '" + e.identifier + "' is invalid.", t.type.warn, "Playlist");
                return false
            },
            _count: function() {
                return this._items.length
            },
            current: function() {
                if (this._current_index !== null) {
                    return this._items[this._current_index]
                }
                return null
            },
            item: function(e) {
                var t, n = this._count();
                for (t = 0; t < n; t += 1) {
                    if (this._items[t].identifier === e) {
                        return this._items[t]
                    }
                }
                return null
            },
            "goto": function(e) {
                var n, r = this._count();
                for (n = 0; n < r; n += 1) {
                    if (this._items[n].identifier === e) {
                        var i = this.current();
                        this._current_index = n;
                        this.events.trigger(this.events.type.PLAYLIST_CURRENT_CHANGE, i);
                        return true
                    }
                }
                t.log("goto() - invalid identifier passed '" + e + "'.  This was not found in the current playlist!", t.type.warn, "Playlist");
                return false
            },
            hasNext: function() {
                if (this._current_index + 1 < this._count()) {
                    return true
                }
                return false
            },
            remove: function(e) {
                var n, r = this._count();
                for (n = 0; n < r; n += 1) {
                    if (this._items[n].identifier === e) {
                        if (this.current().identifier === e) {
                            t.log("remove() -- sorry, you may not remove the current item in the playlist. returning false.)", t.type.warn, "Playlist");
                            return false
                        }
                        this._items.splice(n, 1);
                        if (this._current_index > 0 && n <= this._current_index) {
                            this._current_index -= 1
                        }
                        return true
                    }
                }
                return false
            },
            next: function() {
                if (this._current_index !== null) {
                    t.log("next() advancing to next position in playlist (or to beginning if at last)", t.type.info, "Playlist");
                    var e = this.current();
                    this._current_index = this._current_index + 1 < this._count() ? this._current_index + 1 : 0;
                    this.events.trigger(this.events.type.PLAYLIST_CURRENT_CHANGE, e)
                } else {
                    return false
                }
            },
            previous: function() {
                if (this._current_index !== null) {
                    t.log("previous() moving to previous position in playlist (or to last if at beginning)", t.type.info, "Playlist");
                    var e = this.current();
                    this._current_index = this._current_index - 1 >= 0 ? this._current_index - 1 : this._count() - 1;
                    this.events.trigger(this.events.type.PLAYLIST_CURRENT_CHANGE, e)
                
                } else {
                    return false
                }
            }
        };
        var l;
        return {
            getPlayer: function() {
                if (typeof l === "undefined") {
                    l = new a;
                    l.constructor = null
                }
                return l
            },
            getPlayable: function(e) {
                return new u(e)
            },
            getPlaylist: function() {
                return new f
            },
            getCustomSchemes: function() {
                return o
            }
        }
    }()
}
var scheme_map = {
    apm_audio: {
        flash_server_url: "rtmp://archivemedia.publicradio.org/music",
        flash_file_prefix: "mp3:ondemand",
        http_file_prefix: "http://ondemand.publicradio.org",
        buffer_time: 3,
        type: "audio"
    },
    apm_live_audio: {
        mpr_news: {
            flash_server_url: "rtmp://wowza.stream.publicradio.org/news",
            flash_file_path: "news.stream",
            http_file_path: "http://nis.stream.publicradio.org/nis.mp3",
            buffer_time: 6,
            type: "live_audio"
        },
        mpr_current: {
            flash_server_url: "rtmp://wowza.stream.publicradio.org/kcmp",
            flash_file_path: "kcmp.stream",
            http_file_path: "http://current.stream.publicradio.org/kcmp.mp3",
            buffer_time: 6,
            type: "live_audio"
        }
    }
};
var custom_schemes = APMPlayerFactory.getCustomSchemes();
custom_schemes.init(scheme_map);
var APMPlayer = APMPlayerFactory.getPlayer();
APMPlayer.init()