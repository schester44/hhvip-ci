(function(e) {
    "use strict";
    var t = function(t, n) {
        var r = this;
        this.parent_id = "#" + t.attr("id");
        this.args = n;
        this.init = function() {
            r.main.init();
            r.controls.init();
            r.events.init();
            r.playlist.init()
        };
        this.main = {
            settings: {
                autoplay: false,
                muted: false,
                fetchMetadata: false,
                volume: .9
            },
            isReady: false,
            init: function() {
                if (r.args.hasOwnProperty("settings")) {
                    r.main.mergeSettings(r.args.settings)
                }
                if (r.main.settings.autoplay === true && r.main.settings.fetchMetadata === true) {
                    r.main.settings.autoplay = "wait"
                }
            },
            mergeSettings: function(e) {
                var t;
                for (t in e) {
                    if (e.hasOwnProperty(t) && r.main.settings.hasOwnProperty(t)) {
                        r.main.settings[t] = e[t]
                    }
                }
            },
            canAutoPlay: function() {
                if (r.main.settings.autoplay === true && APMPlayer.mechanism.getCurrentSolution() !== APMPlayer.mechanism.type.HTML5 && r.main.isReady === true) {
                    return true
                }
                return false
            },
            updateAutoPlay: function(e) {
                var t = r.playlist.current();
                if (t.identifier === e.identifier && r.main.settings.autoplay === "wait") {
                    r.main.settings.autoplay = true
                }
            },
            fetchMetadata: function(e) {
                r.events.onFetchMetadata(e)
            }
        };
        this.skin = {
            css: {
                play: "apm_player_play",
                pause: "apm_player_pause",
                seeker: "apm_player_bar",
                seekerBufferingCls: "buffering",
                seekerLoading: "apm_player_loading",
                liveStreamingCls: "streaming",
                volumeWrapper: "apm_player_volume_wrapper",
                volumeMutedCls: "muted",
                volumeBar: "apm_volume_bar",
                volumeBarWrapper: "apm_player_volume_slider_wrapper",
                volumeStatus: "apm_player_volume_status",
                info: "apm_player_info",
                status: "apm_player_status",
                statusWarningCls: "warning",
                statusAlertCls: "alert",
                playtime: "apm_player_playtime",
                playlist: "apm_playlist",
                playlistNowPlayingCls: "nowplaying",
                sponsorOverlayActiveCls: "preroll-active",
                sponsorOverlayInactiveCls: "preroll-inactive",
                sponsorTimer: "apm_sponsor_overlay_time",
                sharingTools: "apm_sharing_tools",
                sharingTabControls: "apm_sharing_tab_controls",
                sharingTabCls: "apm_sharing_tab",
                sharingTabSharing: "apm_sharing_share",
                sharingTabDownload: "apm_sharing_download",
                sharingTabEmbed: "apm_sharing_embed",
                sharingTabLink: "apm_sharing_link"
            }
        };
        this.controls = {
            init: function() {
                r.controls.seeker.init();
                r.controls.info.init();
                r.controls.volume.init();
                r.controls.volumeStatus.init();
                r.controls.pause.init();
                r.controls.play.init();
                r.controls.tools.init()
            },
            play: {
                init: function() {
                    e(r.parent_id + " #" + r.skin.css.play).click(function() {
                        APMPlayer.play(r.playlist.current(), r.main.settings)
                    })
                }
            },
            pause: {
                init: function() {
                    e(r.parent_id + " #" + r.skin.css.pause).click(function() {
                        APMPlayer.pause()
                    })
                }
            },
            seeker: {
                status: "NORMAL",
                init: function() {
                    e(r.parent_id + " #" + r.skin.css.seeker).slider({
                        disabled: true,
                        range: "min",
                        start: function(e, t) {
                            r.controls.seeker.status = "USER_SLIDING"
                        },
                        stop: function(e, t) {
                            r.events.onSeek(t.value / 100);
                            r.controls.seeker.status = "NORMAL"
                        },
                        slide: function(e, t) {
                            r.controls.seeker.status = "USER_SLIDING";
                            var n = r.playlist.current();
                            n.position = t.value / 100 * n.duration;
                            r.controls.playtime.render(n)
                        }
                    })
                },
                update: function(t) {
                    if (r.controls.seeker.status === "NORMAL") {
                        var n = 100 * t.percent_played;
                        e(r.parent_id + " #" + r.skin.css.seeker).slider("value", n)
                    }
                    if (t.percent_loaded <= 1) {
                        var i = 100 * t.percent_loaded;
                        e(r.parent_id + " #" + r.skin.css.seekerLoading).width(i + "%")
                    }
                },
                enable: function() {
                    if (r.playlist.current().type !== APMPlayer.mediaTypes.LIVE_AUDIO) {
                        e(r.parent_id + " #" + r.skin.css.seeker).slider("enable")
                    }
                },
                disable: function() {
                    if (r.playlist.current().type === APMPlayer.mediaTypes.LIVE_AUDIO) {
                        e(r.parent_id + " #" + r.skin.css.seeker).slider("disable")
                    }
                },
                reset: function() {
                    e(r.parent_id + " #" + r.skin.css.seeker).slider("value", 0)
                },
                configure: function(t) {
                    r.controls.seeker.reset();
                    if (t.type === APMPlayer.mediaTypes.LIVE_AUDIO) {
                        r.controls.seeker.disable();
                        e(r.parent_id + " #" + r.skin.css.seeker).addClass(r.skin.css.liveStreamingCls)
                    } else {
                        if (t.duration > 0) {
                            r.controls.seeker.enable()
                        }
                        e(r.parent_id + " #" + r.skin.css.seeker).removeClass(r.skin.css.liveStreamingCls)
                    }
                }
            },
            playtime: {
                convertToTime: function(e) {
                    var t = new Date(e),
                        n = t.getUTCHours(),
                        r = t.getUTCMinutes(),
                        i = t.getUTCSeconds(),
                        s = n,
                        o = s > 0 && r < 10 ? "0" + r : r,
                        u = i < 10 ? "0" + i : i;
                    return (s > 0 ? s + ":" : "") + (o + ":") + u
                },
                render: function(t) {
                    var n;
                    if (t.duration > 0) {
                        n = r.controls.playtime.convertToTime(t.position) + " / " + r.controls.playtime.convertToTime(t.duration)
                    } else {
                        n = r.controls.playtime.convertToTime(t.position)
                    }
                    e(r.parent_id + " #" + r.skin.css.playtime).text(n)
                }
            },
            volume: {
                init: function() {
                    e(r.parent_id + " #" + r.skin.css.volumeBar).slider({
                        range: "min",
                        orientation: "vertical",
                        value: r.main.settings.volume * 100,
                        stop: function(e, t) {
                            APMPlayer.setVolume(t.value / 100);
                            if (t.value > 0) {
                                if (r.main.settings.muted) {
                                    APMPlayer.unmute();
                                    r.controls.volumeStatus.renderUnmuted();
                                    r.main.settings.muted = false
                                }
                            }
                        }
                    })
                },
                renderMuted: function() {
                    e(r.parent_id + " #" + r.skin.css.volumeBar).slider("value", 0)
                },
                renderUnmuted: function() {
                    e(r.parent_id + " #" + r.skin.css.volumeBar).slider("value", r.main.settings.volume * 100)
                }
            },
            volumeStatus: {
                init: function() {
                    e(r.parent_id + " #" + r.skin.css.volumeStatus).click(function() {
                        if (r.main.settings.muted) {
                            APMPlayer.unmute();
                            r.main.settings.muted = false;
                            r.controls.volume.renderUnmuted();
                            r.controls.volumeStatus.renderUnmuted()
                        } else {
                            APMPlayer.mute();
                            r.main.settings.muted = true;
                            r.controls.volume.renderMuted();
                            r.controls.volumeStatus.renderMuted()
                        }
                    })
                },
                renderMuted: function() {
                    e(r.parent_id + " #" + r.skin.css.volumeStatus).addClass(r.skin.css.volumeMutedCls)
                },
                renderUnmuted: function() {
                    e(r.parent_id + " #" + r.skin.css.volumeStatus).removeClass(r.skin.css.volumeMutedCls)
                }
            },
            info: {
                init: function() {
                    if (r.args.hasOwnProperty("onMetadata")) {
                        r.events.onMetadata = r.args.onMetadata
                    }
                }
            },
            status: {
                displayWarning: function(t) {
                    e(r.parent_id + " #" + r.skin.css.status).html(t);
                    e(r.parent_id + " #" + r.skin.css.status).addClass(r.skin.css.statusWarningCls)
                },
                displayAlert: function(t) {
                    e(r.parent_id + " #" + r.skin.css.status).html(t);
                    e(r.parent_id + " #" + r.skin.css.status).addClass(r.skin.css.statusAlertCls)
                },
                clearAll: function() {
                    e(r.parent_id + " #" + r.skin.css.status).removeClass(r.skin.css.statusAlertCls);
                    e(r.parent_id + " #" + r.skin.css.status).removeClass(r.skin.css.statusWarningCls);
                    e(r.parent_id + " #" + r.skin.css.status).html("")
                }
            },
            tools: {
                init: function() {
                    if (r.args.hasOwnProperty("tools") && r.args.tools.hasOwnProperty("config")) {
                        r.controls.tools.config = r.args.tools.config
                    }
                    r.controls.tools.config()
                },
                config: function() {
                    e(r.parent_id + " #" + r.skin.css.sharingTools + " ." + r.skin.css.sharingTabCls).hide();
                    e(r.parent_id + " #" + r.skin.css.sharingTools + " ." + r.skin.css.sharingTabCls + ":first").show();
                    e(r.parent_id + " #" + r.skin.css.sharingTools + " ul#" + r.skin.css.sharingTabControls + " li:first").addClass("active");
                    e(r.parent_id + " #" + r.skin.css.sharingTools + " ul#" + r.skin.css.sharingTabControls + " li a").click(function() {
                        e(r.parent_id + " #" + r.skin.css.sharingTools + " ul#" + r.skin.css.sharingTabControls + " li").removeClass("active");
                        e(this).parent().addClass("active");
                        var t = e(this).attr("href");
                        e(r.parent_id + " #" + r.skin.css.sharingTools + " ." + r.skin.css.sharingTabCls).hide();
                        e(t).show();
                        return false
                    })
                },
                renderDownload: function(t) {
                    var n = "";
                    if (t.downloadable === true && r.playlist.current().type === APMPlayer.mediaTypes.AUDIO) {
                        n = '<a href="' + APMPlayer.base_path + "util/download.php?uri=" + t.http_file_path + '">file download</a>'
                    } else {
                        n = "sorry, this item is not downloadable."
                    }
                    e(r.parent_id + " #" + r.skin.css.sharingTools + " #" + r.skin.css.sharingTabDownload).html(n)
                }
            }
        };
        this.events = {
            init: function() {
                APMPlayer.events.addListener(APMPlayer.events.type.PLAYER_READY, r.events.onPlayerReady);
                APMPlayer.events.addListener(APMPlayer.events.type.PLAYING, r.events.onPlaying);
                APMPlayer.events.addListener(APMPlayer.events.type.PAUSED, r.events.onPaused);
                APMPlayer.events.addListener(APMPlayer.events.type.METADATA, r.events.onMetadata);
                APMPlayer.events.addListener(APMPlayer.events.type.BUFFER_START, r.events.onBufferStart);
                APMPlayer.events.addListener(APMPlayer.events.type.BUFFER_END, r.events.onBufferEnd);
                APMPlayer.events.addListener(APMPlayer.events.type.POSITION_UPDATE, r.events.onPositionUpdate);
                APMPlayer.events.addListener(APMPlayer.events.type.FINISHED, r.events.onFinished);
                APMPlayer.events.addListener(APMPlayer.events.type.UNLOADED, r.events.onUnloaded);
                APMPlayer.events.addListener(APMPlayer.events.type.VOLUME_UPDATED, r.events.onVolumeUpdated);
                APMPlayer.events.addListener(APMPlayer.events.type.CONNECTION_LOST, r.events.onConnectionLost);
                APMPlayer.events.addListener(APMPlayer.events.type.PLAYER_FAILURE, r.events.onFailure);
                APMPlayer.events.addListener(APMPlayer.events.type.MISSING_FILE, r.events.onMissingFile)
            },
            onPlayerReady: function() {
                r.main.isReady = true;
                if (r.main.canAutoPlay()) {
                    APMPlayer.play(r.playlist.current(), r.main.settings)
                } else {
                    var e = r.playlist.current();
                    if (e !== null) {
                        r.events.onMetadata(e)
                    }
                }
            },
            onPlaying: function(t) {
                r.controls.seeker.enable();
                r.controls.status.clearAll();
                e(r.parent_id + " #" + r.skin.css.play).hide();
                e(r.parent_id + " #" + r.skin.css.pause).show();
                r.playlist.addNowPlaying(t)
            },
            onPaused: function(t) {
                e(r.parent_id + " #" + r.skin.css.play).show();
                e(r.parent_id + " #" + r.skin.css.pause).hide();
                if (t.type === APMPlayer.mediaTypes.LIVE_AUDIO) {
                    r.controls.seeker.reset()
                }
            },
            onFinished: function() {
                r.controls.seeker.disable();
                if (r.playlist.hasNext()) {
                    r.playlist.next()
                } else {
                    APMPlayer.unload()
                }
            },
            onPositionUpdate: function(e) {
                r.controls.seeker.update(e);
                if (r.controls.seeker.status !== "USER_SLIDING") {
                    r.controls.playtime.render(e)
                }
            },
            onSeek: function(e) {
                if (APMPlayer.state.current() !== APMPlayer.state.type.STOPPED) {
                    APMPlayer.seek(e)
                } else {
                    var t = r.playlist.current();
                    if (t.duration > 0) {
                        t.percent_played = e;
                        t.position = t.duration * e;
                        r.controls.playtime.render(t)
                    }
                }
            },
            onUnloaded: function(t) {
                r.controls.seeker.reset();
                r.controls.playtime.render(t);
                e(r.parent_id + " #" + r.skin.css.play).show();
                e(r.parent_id + " #" + r.skin.css.pause).hide()
            },
            onBufferStart: function() {
                if (APMPlayer.state.current() === APMPlayer.state.type.PLAYING) {
                    e(r.parent_id + " #" + r.skin.css.seeker).addClass(r.skin.css.seekerBufferingCls)
                }
            },
            onBufferEnd: function() {
                e(r.parent_id + " #" + r.skin.css.seeker).removeClass(r.skin.css.seekerBufferingCls)
            },
            onFetchMetadata: function(e) {
                APMPlayer.debug.log("events.onFetchMetadata() pass-through hit for  '" + e.identifier + "'", APMPlayer.debug.type.info, "APMPlayerUI");
                var t = r.playlist.current();
                if (e.identifier === t.identifier) {
                    r.events.onMetadata(t);
                    r.controls.tools.renderDownload(t);
                    if (t.duration > 0) {
                        r.controls.playtime.render(t);
                        if (t.isFlashStreamable()) {
                            r.controls.seeker.enable();
                            if (t.position > 0) {
                                t.percent_played = t.position / t.duration
                            }
                            r.controls.seeker.update(t)
                        }
                    }
                    r.main.updateAutoPlay(t);
                    if (r.main.canAutoPlay()) {
                        APMPlayer.play(r.playlist.current(), r.main.settings)
                    }
                }
            },
            onMetadata: function() {},
            onFailure: function() {
                var e = r.playlist.current();
                var t = '<p>We\'re sorry, but your browser is not able to play the stream.  Please try one of these options:  <br /><br />1) Install or enable <a href="http://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player</a> <br />2) Use a browser that supports HTML5 and MP3 audio, such as <a href="http://www.google.com/chrome/" target="_blank">Chrome</a>, <a href="http://www.apple.com/safari/download/" target="_blank">Safari</a>, or <a href="http://www.microsoft.com/ie9" target="_blank">Internet Explorer 9</a>';
                if (/Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor)) {
                    t = '<p>We\'re sorry, but your browser is not able to play the stream.  Please try one of these options:  <br /><br />1) Install or enable <a href="http://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player</a> <br />2) Install <a href="http://www.apple.com/quicktime/download/" target="_blank">Quicktime</a> for HTML5 support for Safari on Windows (requires reboot) <br />2b) Use a different browser that natively supports HTML5 and MP3 audio, such as <a href="http://www.google.com/chrome/" target="_blank">Chrome</a> or <a href="http://www.microsoft.com/ie9" target="_blank">Internet Explorer 9</a>'
                }
                if (e.downloadable === true && e.http_file_path !== "") {
                    if (e.type === APMPlayer.mediaTypes.AUDIO) {
                        t += '<br />3) <a href="' + APMPlayer.base_path + "util/download.php?uri=" + e.http_file_path + '">download the audio</a>'
                    } else if (e.type === APMPlayer.mediaTypes.LIVE_AUDIO) {
                        t += '<br />3) <a href="' + e.http_file_path + '">Stream the audio using a third-party player (eg. iTunes)</a>'
                    }
                }
                t += "</p>";
                r.controls.status.displayWarning(t)
            },
            onVolumeUpdated: function(e) {
                r.main.settings.volume = e.percent_decimal
            },
            onConnectionLost: function() {
                APMPlayer.unload();
                var e = "<p>Your network connection has changed or has been lost.<br /><br />Please check your connection, then click play to resume.";
                r.controls.status.displayAlert(e)
            },
            onMissingFile: function(e) {
                var t = "<p>We're sorry, an error has occurred and your audio cannot be played at this time. <br /><br />Often, this error is a result of a poor or missing internet connection.  Please check your internet connection, then click play to resume.";
                r.controls.status.displayWarning(t)
            }
        };
        this.playlist = APMPlayerFactory.getPlaylist();
        r.playlist.onUpdate = function(e) {};
        r.playlist.init = function() {
            if (r.args.hasOwnProperty("onPlaylistUpdate")) {
                r.playlist.onUpdate = r.args.onPlaylistUpdate
            }
            if (r.args.hasOwnProperty("playables")) {
                e.each(r.args.playables, function(e, t) {
                    r.playlist.addPlayable(t)
                })
            }
        };
        r.playlist.addPlayable = function(e) {
            var t = APMPlayerFactory.getPlayable(e);
            if (t.isValid()) {
                r.playlist.add(t);
                r.playlist.onUpdate(t);
                if (r.main.settings.fetchMetadata === true) {
                    if (t.isCustomScheme("apm_audio")) {
                        r.main.fetchMetadata(t)
                    } else {
                        r.main.updateAutoPlay(t)
                    }
                }
            } else {
                APMPlayer.debug.log("sorry, there was a problem with the parameters passed and a valid playable could not be created.", APMPlayer.debug.type.warn, "APMPlayerUI")
            }
        };
        r.playlist.gotoItem = function(e) {
            if (APMPlayer.state.current() === APMPlayer.state.type.STOPPED || r.playlist.current().identifier !== e) {
                r.controls.seeker.disable();
                r.playlist.goto(e)
            }
        };
        r.playlist.addNowPlaying = function(t) {
            e("li[ id = '" + t.identifier + "']").addClass(r.skin.css.playlistNowPlayingCls)
        };
        r.playlist.removeNowPlaying = function(t) {
            e(r.parent_id + " #" + r.skin.css.playlist + " li[ id = '" + t.identifier + "']").removeClass(r.skin.css.playlistNowPlayingCls)
        };
        r.playlist.onCurrentChange = function(e) {
            if (e !== null) {
                r.playlist.removeNowPlaying(e);
                APMPlayer.play(r.playlist.current(), r.main.settings)
            }
            var t = r.playlist.current();
            r.controls.seeker.configure(t);
            r.controls.tools.renderDownload(t);
            if (t.duration > 0) {
                r.controls.playtime.render(t)
            }
        };
        r.playlist.events.addListener(APMPlayer.events.type.PLAYLIST_CURRENT_CHANGE, r.playlist.onCurrentChange);
        r.init()
    };
    e.fn.apmplayer_ui = function(n) {
        if (typeof APMPlayer === "undefined" || typeof soundManager === "undefined") {
            e.error("apmplayer_ui ERROR.  1 or more dependent libraries missing.  exiting.");
            return null
        }
        var r = this,
            i = {
                addPlayable: function(e) {
                    if (typeof window.apmplayer_ui !== "undefined") {
                        window.apmplayer_ui.playlist.addPlayable(e)
                    } else {
                        APMPlayer.debug.log("you must first initialize apmplayer_ui before calling methods on it.", APMPlayer.debug.type.error, "APMPlayerUI")
                    }
                },
                gotoPlaylistItem: function(e) {
                    if (typeof window.apmplayer_ui !== "undefined") {
                        window.apmplayer_ui.playlist.gotoItem(e)
                    } else {
                        APMPlayer.debug.log("you must first initialize apmplayer_ui before calling methods on it.", APMPlayer.debug.type.error, "APMPlayerUI")
                    }
                }
            };
        if (i[n]) {
            return i[n].apply(this, Array.prototype.slice.call(arguments, 1))
        } else if (typeof n === "object" || !n) {
            if (typeof window.apmplayer_ui === "undefined") {
                window.apmplayer_ui = new t(r, n);
                APMPlayer.debug.log("instantiated apmplayer_ui", APMPlayer.debug.type.info, "APMPlayerUI")
            } else {
                APMPlayer.debug.log("sorry, only one player UI instance is currently supported.", APMPlayer.debug.type.error, "APMPlayerUI")
            }
        } else {
            APMPlayer.debug.log("Method " + n + " does not exist on jQuery.apmplayer_ui", APMPlayer.debug.type.error, "APMPlayerUI")
        }
    }
})(jQuery)