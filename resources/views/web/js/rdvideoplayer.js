/**
* @module       RD Video Player
* @author       Rafael Shayvolodyan
* @see          https://ua.linkedin.com/in/rafael-shayvolodyan-3a297b96
* @version      1.0.1
*/
(function () {
    (function (e, f, k) {
        var m, n, q, r, p, l, g;
        l = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        p = /iphone|ipod|ipad/.test(k.navigator.userAgent.toLowerCase());
        g = "ontouchstart" in k;
        n = -1 < navigator.userAgent.toLowerCase().indexOf("chrome");
        q = -1 < navigator.userAgent.toLowerCase().indexOf("firefox");
        r = -1 < navigator.userAgent.indexOf("Trident/") || -1 < navigator.userAgent.indexOf("Edge/");
        m = (function () {
            function c(b, d) {
                this.options = e.extend(!0, {}, this.Defaults, d);
                this.element = b;
                this.$element = e(b);
                this.state = 0;
                this.$win = e(k);
                this.$doc = e(f);
                this.isFullscreen = !1;
                this.thread = null;
                this.loaded = !1;
                this.classes = {
                    playPause: "rd-video-play-pause",
                    stopButton: "rd-video-stop",
                    nextButton: "rd-video-next",
                    prevButton: "rd-video-prev",
                    volume: "rd-video-volume",
                    volumeBar: "rd-video-volume-bar",
                    volumeBarSlider: "rd-video-volume-bar-slider",
                    progressBar: "rd-video-progress-bar",
                    progressBarSlider: "rd-video-progress-bar-slider",
                    duration: "rd-video-duration",
                    currentTime: "rd-video-current-time",
                    playlist: "rd-video-playlist",
                    title: "rd-video-title",
                    fullscreen: "rd-video-fullscreen",
                    preview: "rd-video-preview",
                };
                this.initialize();
            }
            c.prototype.Defaults = {
                path: "",
                volumeBarType: "vertical",
                title: "",
                ieFullScreenClass: "rd-video-wrap",
                volume: 1,
                dblClickFull: !0,
                playPauseOnClick: !0,
                hideControls: !0,
                preload: !0,
                preview: "",
                muted: !1,
                callbacks: { onEnded: null, onPaused: null, onPlay: null },
            };
            c.prototype.initialize = function () {
                this.createDataApi();
                this.initPlaylistClickListener();
                this.createVideo();
                this.createProgressBar();
                this.createVolumeBar();
                this.attachPlayerEvents();
                this.attachPlayPauseButtonListener();
                this.attachVolumeButtonListener();
                this.attachStopButtonListener();
                this.updateDuration();
                this.updateTitle();
                this.attachNextTrackButtonListener();
                this.attachPrevTrackButtonListener();
                this.attachFullScreenButtonListener();
            };
            c.prototype.createVideo = function () {
                this.video = this.$element.find("video")[0];
                this.options.muted && (this.video.muted = !0);
                e(this.video).append('<source type="video/mp4">');
                null != this.playlist ? this.loadVideo(0) : this.loadVideo(this.options.path);
                this.options.preload && (this.video.load(), (this.loaded = !0));
            };
            c.prototype.updateState = function (b) {
                var d;
                d = b.isErrored() ? 0 : b.isLoading() ? 1 : 2;
                b.state !== d && (b.state = d);
            };
            c.prototype.attachPlayPauseButtonListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.playPause).on("click", function (d) {
                    d.preventDefault();
                    b.loaded || (b.video.load(), (b.loaded = !0));
                    e.proxy(b.playPause(), b, this);
                });
            };
            c.prototype.playPause = function () {
                this.isPlaying() ? this.video.pause() : this.video.play();
            };
            c.prototype.attachStopButtonListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.stopButton).on("click", function (d) {
                    d.preventDefault();
                    b.video.pause();
                    b.video.currentTime = 0;
                });
            };
            c.prototype.updateDuration = function () {
                var b, d;
                b = this;
                (d = b.$element.find("." + b.classes.duration)[0]) &&
                    b.video.addEventListener("durationchange", function () {
                        var a, c, e;
                        a = b.getDuration();
                        e = ("0" + (Math.floor(a) % 60)).slice(-2);
                        c = ("0" + Math.floor((a / 60) % 60)).slice(-2);
                        a = 3600 >= a ? "" : ("0" + Math.floor((a / 3600) % 60) + ":").slice(-2);
                        return (d.innerHTML = "" + a + c + ":" + e);
                    });
            };
            c.prototype.updateTitle = function () {
                var b, d;
                b = this;
                (d = b.$element.find("." + b.classes.title)[0]) &&
                    b.video.addEventListener("durationchange", function () {
                        var a;
                        null != b.playlist ? ((a = e("." + b.classes.playlist + " .video-active")), (a = a[0].getAttribute("data-rd-video-title"))) : (a = b.options.title);
                        return (d.innerHTML = a);
                    });
            };
            c.prototype.updateCurrentTime = function (b) {
                var d, a, c;
                d = this.video.currentTime;
                c = ("0" + (Math.floor(d) % 60)).slice(-2);
                a = ("0" + Math.floor((d / 60) % 60)).slice(-2);
                d = 3600 >= d ? "" : ("0" + Math.floor((d / 3600) % 60) + ":").slice(-2);
                b.innerHTML = "" + d + a + ":" + c;
            };
            c.prototype.attachVolumeButtonListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.volume).on("click", function (d) {
                    d.preventDefault();
                    b.video.muted = !b.isMuted();
                });
            };
            c.prototype.attachNextTrackButtonListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.nextButton).on("click", function (d) {
                    d.preventDefault();
                    return b.playNextVideo(!0);
                });
            };
            c.prototype.playNextVideo = function (b) {
                var d;
                d = parseInt(e(this.playlist).find(".video-active").attr("data-index"));
                e(this.playlist).find('li[data-index="' + (d + 1) + '"]').length
                    ? (this.loadVideo(d + 1), this.video.load(), (this.loaded = !0), this.video.play())
                    : b && (this.loadVideo(0), this.video.load(), (this.loaded = !0), this.video.play());
            };
            c.prototype.attachPrevTrackButtonListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.prevButton).on("click", function (d) {
                    var a, c;
                    d.preventDefault();
                    d = parseInt(e(b.playlist).find(".video-active").attr("data-index"));
                    a = e(b.playlist).find("[data-index]").length;
                    c = b.isPlaying();
                    0 !== d ? b.loadVideo(d - 1) : b.loadVideo(a - 1);
                    b.loaded = !0;
                    b.video.load();
                    c ? b.video.play() : b.video.pause();
                });
            };
            c.prototype.attachFullScreenButtonListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.fullscreen).on("click", function (d) {
                    d.preventDefault();
                    b.makeFullscreen(b);
                });
            };
            c.prototype.makeFullscreen = function (b) {
                b.isFullscreen
                    ? f.cancelFullScreen
                        ? f.cancelFullScreen()
                        : f.mozCancelFullScreen
                        ? f.mozCancelFullScreen()
                        : f.webkitCancelFullScreen
                        ? f.webkitCancelFullScreen()
                        : f.msFullscreenElement
                        ? f.msExitFullscreen()
                        : p && b.video.webkitEnterFullscreen()
                    : b.video.requestFullscreen
                    ? b.video.requestFullscreen()
                    : b.video.mozRequestFullScreen
                    ? b.$element[0].mozRequestFullScreen()
                    : b.video.webkitRequestFullscreen
                    ? b.video.webkitRequestFullscreen()
                    : b.video.msRequestFullscreen
                    ? b.$element.find("." + b.options.ieFullScreenClass)[0].msRequestFullscreen()
                    : p && b.video.webkitEnterFullscreen();
                b.$doc.on("webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange", function () {
                    b.isFullscreen = f.fullScreen || f.mozFullScreen || f.webkitIsFullScreen || f.msFullscreenElement;
                    b.isFullscreen ? (b.element.classList.add("fullscreen"), n && l && b.video.setAttribute("controls", "true")) : (b.element.classList.remove("fullscreen"), n && l && b.video.removeAttribute("controls"));
                });
            };
            c.prototype.removeStateClass = function (b) {
                var d, a, c, e;
                a = b.element.className.split(/\s+/);
                c = 0;
                for (e = a.length; c < e; c++) (d = a[c]), -1 !== d.indexOf("state-") && b.element.classList.remove(d);
            };
            c.prototype.createProgressBar = function () {
                var b, d, a, c;
                a = this;
                a.$element.find("." + a.classes.progressBar).length &&
                    ((a.currentProgress = c = f.createElement("div")),
                    c.classList.add("current"),
                    a.attachProgressBarSlider(),
                    (b = a.$element.find("." + a.classes.progressBar)[0]),
                    (d = !1),
                    e(b).on("mousedown.rd touchstart.rd", function (c) {
                        if (1 === c.which || g)
                            (d = !0),
                                (a.video.currentTime = a.getClickBarPosition(c, b, "horizontal") * a.getDuration()),
                                a.$doc.on("mouseup.rd touchend.rd touchcancel.rd", function (a) {
                                    if (d) return (d = !1);
                                }),
                                a.$doc.on("mousemove.rd touchmove.rd", function (c) {
                                    d && (a.video.currentTime = a.getClickBarPosition(c, b, "horizontal") * a.getDuration());
                                });
                    }),
                    b.appendChild(c));
            };
            c.prototype.attachProgressBarSlider = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.progressBarSlider).length &&
                    ((b.progressSlider = b.$element.find("." + b.classes.progressBarSlider)[0]),
                    b.video.addEventListener("seeked", function () {
                        return (b.progressSlider.style.left = (b.video.currentTime / b.getDuration()) * 100 + "%");
                    }));
            };
            c.prototype.createVolumeBar = function () {
                var b, d, a, c;
                a = this;
                a.$element.find("." + a.classes.volumeBar).length &&
                    ((a.currentVolume = c = f.createElement("div")),
                    c.classList.add("current"),
                    (c.style.width = 100 * a.video.volume + "%"),
                    a.attachVolumeBarSlider(),
                    (b = a.$element.find("." + a.classes.volumeBar)[0]),
                    a.getOption("volumeBarType") && b.classList.add("rd-video-volume-bar-" + a.getOption("volumeBarType")),
                    (d = !1),
                    e(b).on("mousedown.rd touchstart.rd", function (c) {
                        if (1 === c.which || g)
                            (d = !0),
                                (a.video.volume = a.getClickBarPosition(c, b, a.getOption("volumeBarType"))),
                                a.isMuted() && (a.video.muted = !1),
                                a.$doc.on("mouseup.rd touchend.rd touchcancel.rd", function (a) {
                                    if (d) return (d = !1);
                                }),
                                a.$doc.on("mousemove.rd touchmove.rd", function (c) {
                                    (1 === c.which || g) && d && ((a.video.volume = a.getClickBarPosition(c, b, a.getOption("volumeBarType"))), a.isMuted() && (a.video.muted = !1));
                                });
                    }),
                    b.appendChild(c));
            };
            c.prototype.attachVolumeBarSlider = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.volumeBarSlider).length &&
                    ((b.volumeBarSlider = b.$element.find("." + b.classes.volumeBarSlider)[0]),
                    b.video.addEventListener("volumechange", function () {
                        if ("horizontal" === b.getOption("volumeBarType")) return b.isMuted() ? (b.volumeBarSlider.style.left = "0") : (b.volumeBarSlider.style.left = 100 * b.video.volume + "%");
                        if ("vertical" === b.getOption("volumeBarType")) return b.isMuted() ? (b.volumeBarSlider.style.bottom = "0") : (b.volumeBarSlider.style.bottom = 100 * b.video.volume + "%");
                    }));
            };
            c.prototype.initPlaylistClickListener = function () {
                var b;
                b = this;
                b.$element.find("." + b.classes.playlist).length &&
                    ((b.playlist = b.$element.find("." + b.classes.playlist)[0]),
                    b.setPlaylistIndexes(),
                    e(b.playlist)
                        .find(b.playlist.getAttribute("data-rd-video-play-on"))
                        .on("click", function (d) {
                            var a;
                            a = e(this);
                            a = a.parents("." + b.classes.playlist + " li").length ? a.parents("." + b.classes.playlist + " li") : a;
                            if (a.hasClass("video-active")) b.video.play();
                            else return b.clearPlayListClasses(), a.addClass("video-active"), "A" === this.tagName && d.preventDefault(), b.loadVideo(parseInt(a.attr("data-index"))), b.video.load(), (b.loaded = !0), b.video.play();
                        }));
            };
            c.prototype.loadVideo = function (b, d) {
                var a, c;
                this.loaded = !1;
                a = e(this.video);
                null != this.playlist && (this.clearPlayListClasses(), (c = e(this.playlist).find("li").eq(b)), c.addClass("video-active"));
                c = "number" === typeof b ? c.attr("data-rd-video-src") : b;
                a.find('source[type*="mp4"]').attr("src", c + ".mp4");
            };
            c.prototype.clearPlayListClasses = function () {
                this.$element.find("." + this.classes.playlist + " .video-active").removeClass("video-active");
            };
            c.prototype.setPlaylistIndexes = function () {
                this.$element.find("." + this.classes.playlist + " li").each(function () {
                    this.setAttribute("data-index", e(this).index());
                });
            };
            c.prototype.attachPlayerEvents = function () {
                var b, c, a, f, g, k, h;
                a = this;
                h = a.video;
                b = e(h);
                f = a.$element.find("." + a.classes.currentTime);
                null == a.playlist && ((g = a.$element.find("." + a.classes.preview)), g.css("background-image", "url(" + a.options.preview + ")"));
                b.on("playing play", function () {
                    a.updateState(a);
                    a.removeStateClass(a);
                    a.element.classList.add("state-playing");
                    null == a.playlist && g.removeClass("show");
                    if (a.options.callbacks.onPlay) return a.options.callbacks.onPlay.call(this, a.video);
                });
                if (r)
                    b.on("seeked", function () {
                        a.updateState(a);
                        a.removeStateClass(a);
                        a.element.classList.add("state-playing");
                        if (a.options.callbacks.onPlay) return a.options.callbacks.onPlay.call(this, a.video);
                    });
                h.addEventListener("pause", function () {
                    a.updateState(a);
                    a.removeStateClass(a);
                    a.element.classList.add("state-pause");
                    if (a.options.callbacks.onPaused) return a.options.callbacks.onPaused.call(this, a.video);
                });
                h.addEventListener("ended", function () {
                    a.options.loop && ((a.video.currentTime = 0), a.video.play());
                    a.playlist ? a.playNextVideo(!1) : a.video.pause();
                    if (a.options.callbacks.onEnded) return a.options.callbacks.onEnded.call(this, a.video);
                });
                b.on("volumechange canplay canplaythrough", function () {
                    if (a.isMuted() || 0 === a.video.volume) {
                        if ((a.element.classList.add("muted"), (a.video.muted = !0), null != a.currentVolume)) {
                            if ("horizontal" === a.getOption("volumeBarType")) return (a.currentVolume.style.width = 0);
                            if ("vertical" === a.getOption("volumeBarType")) return (a.currentVolume.style.height = 0);
                        }
                    } else if ((a.element.classList.remove("muted"), (a.video.muted = !1), null != a.currentVolume)) {
                        if ("horizontal" === a.getOption("volumeBarType")) return (a.currentVolume.style.width = 100 * a.video.volume + "%");
                        if ("vertical" === a.getOption("volumeBarType")) return (a.currentVolume.style.height = 100 * a.video.volume + "%");
                    }
                });
                h.addEventListener("canplay", function () {
                    a.updateState(a);
                    a.element.classList.remove("state-loading");
                    null == a.playlist && g.addClass("show");
                    f.length && a.updateCurrentTime(f[0]);
                    if (null != a.volumeBarSlider) {
                        if ("horizontal" === a.getOption("volumeBarType")) return (a.volumeBarSlider.style.left = 100 * a.video.volume + "%");
                        if ("vertical" === a.getOption("volumeBarType")) return (a.volumeBarSlider.style.bottom = 100 * a.video.volume + "%");
                    }
                });
                h.addEventListener("canplaythrough", function () {
                    a.updateState(a);
                    a.element.classList.remove("state-loading");
                    if (null == a.playlist) return g.addClass("show");
                });
                b.on("timeupdate durationchange", function () {
                    var b;
                    null == a.playlist && g.removeClass("show");
                    null != a.currentProgress && ((b = (h.currentTime / a.getDuration()) * 100 + "%"), (a.currentProgress.style.width = b));
                    null != a.progressSlider && (a.progressSlider.style.left = b);
                    if (f.length) return a.updateCurrentTime(f[0]);
                });
                h.addEventListener("loadstart", function () {
                    a.updateState(a);
                    a.removeStateClass(a);
                    q && (a.video.currentTime = 0);
                    return a.element.classList.add("state-loading");
                });
                h.addEventListener("error", function () {
                    a.updateState(a);
                    a.removeStateClass(a);
                    return a.element.classList.add("state-error");
                });
                h.addEventListener("waiting", function () {
                    a.updateState(a);
                    a.removeStateClass(a);
                    return a.element.classList.add("state-loading");
                });
                a.options.hideControls &&
                    (a.$element.on("mouseleave", function () {
                        return a.element.classList.remove("hovered");
                    }),
                    a.$element.on("mousemove", function () {
                        a.element.classList.add("hovered");
                        clearTimeout(a.thread);
                        return (a.thread = setTimeout(function () {
                            return a.element.classList.remove("hovered");
                        }, 1500));
                    }));
                a.$doc.on("click", function (b) {
                    if (l && !e(b.target).is(a.$element) && 0 === a.$element.find(e(b.target)).length) return a.element.classList.remove("hovered");
                });
                if (l)
                    b.on("click", function (b) {
                        b.preventDefault();
                        return a.element.classList.add("hovered");
                    });
                c = 0;
                k = null;
                b.on("click", function (b) {
                    b.preventDefault();
                    c++;
                    if (1 < c && a.options.dblClickFull) a.makeFullscreen(a), clearInterval(k), (c = 0);
                    else
                        return (k = setTimeout(function () {
                            a.options.playPauseOnClick && !l && a.playPause();
                            return (c = 0);
                        }, 200));
                });
            };
            c.prototype.getClickBarPosition = function (b, c, a) {
                b.preventDefault();
                if ("horizontal" === a) return (a = c.getBoundingClientRect().left), (c = c.clientWidth), b.originalEvent && (b = b.originalEvent), (b = g ? b.targetTouches[0].pageX : b.pageX), (b - a) / c;
                if ("vertical" === a) return (a = c.getBoundingClientRect().bottom), (c = c.clientHeight), b.originalEvent && (b = b.originalEvent), (b = g ? b.targetTouches[0].clientY : b.clientY), Math.min(1, Math.max(0, (a - b) / c));
            };
            c.prototype.isPlaying = function () {
                return this.video && !this.video.paused;
            };
            c.prototype.isPaused = function () {
                return this.video && this.video.paused;
            };
            c.prototype.isMuted = function () {
                return this.video.muted;
            };
            c.prototype.isLoading = function () {
                return !this.state && this.isEmpty() ? !1 : this.video.networkState === this.video.NETWORK_LOADING && this.video.readyState < this.video.HAVE_FUTURE_DATA;
            };
            c.prototype.isErrored = function () {
                return this.video.error || this.video.networkState === this.video.NETWORK_NO_SOURCE;
            };
            c.prototype.isEmpty = function () {
                return this.video.readyState === this.video.HAVE_NOTHING;
            };
            c.prototype.getDuration = function () {
                return this.video.duration;
            };
            c.prototype.createDataApi = function () {
                this.$element.attr("data-rd-video-path") && (this.options.path = this.$element.attr("data-rd-video-path"));
                this.$element.attr("data-volume-bar-type") && (this.options.volumeBarType = this.$element.attr("data-volume-bar-type"));
                this.$element.attr("data-rd-video-title") && (this.options.title = this.$element.attr("data-rd-video-title"));
                this.$element.attr("data-rd-video-preview") && (this.options.preview = this.$element.attr("data-rd-video-preview"));
                this.$element.attr("data-rd-video-muted") && (this.options.muted = this.$element.attr("data-rd-video-muted"));
                this.$element.attr("data-rd-video-fullscreen-class") && (this.options.ieFullScreenClass = this.$element.attr("data-rd-video-fullscreen-class"));
                this.$element.attr("data-rd-video-volume") && (this.options.volume = parseInt(this.$element.attr("data-rd-video-volume")));
                this.$element.attr("data-rd-video-dbl-click-full") && (this.options.dblClickFull = "true" === this.$element.attr("data-rd-video-dbl-click-full"));
                this.$element.attr("data-rd-video-playpause-click") && (this.options.playPauseOnClick = "true" === this.$element.attr("data-rd-video-playpause-click"));
                this.$element.attr("data-rd-video-hide-controls") && (this.options.hideControls = "true" === this.$element.attr("data-rd-video-hide-controls"));
                this.$element.attr("data-rd-video-preload") && (this.options.preload = "false" === this.$element.attr("data-rd-video-preload"));
            };
            c.prototype.getOption = function (b) {
                var c, a;
                for (c in this.options.responsive) c <= k.innerWidth && (a = c);
                return null != this.options.responsive && null != this.options.responsive[a][b] ? this.options.responsive[a][b] : this.options[b];
            };
            return c;
        })();
        e.fn.extend({
            RDVideoPlayer: function (c) {
                return this.each(function () {
                    var b;
                    b = e(this);
                    if (!b.data("RDVideoPlayer")) return b.data("RDVideoPlayer", new m(this, c));
                });
            },
        });
        return (k.RDVideoPlayer = m);
    })(window.jQuery, document, window);
    "undefined" !== typeof module && null !== module
        ? (module.exports = window.RDVideoPlayer)
        : "function" === typeof define &&
          define.amd &&
          define(["jquery"], function () {
              return window.RDVideoPlayer;
          });
  }.call(this));