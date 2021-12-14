/**
* @module      TimeCircles
* @author      Wim Barelds
* @version     1.5.3
* @see         https://www.wimbarelds.nl/
* @license     MIT License
*/
(function (f) {
    function A(a) {
        a = a.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i, function (a, b, d, e) {
            return b + b + d + d + e + e;
        });
        return (a = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(a)) ? { r: parseInt(a[1], 16), g: parseInt(a[2], 16), b: parseInt(a[3], 16) } : null;
    }
    function B() {
        var a = document.createElement("canvas");
        return !(!a.getContext || !a.getContext("2d"));
    }
    function n() {
        return Math.floor(65536 * (1 + Math.random()))
            .toString(16)
            .substring(1);
    }
    function z(a, c, b, d, e) {
        for (var h = {}, l = {}, f = {}, g = {}, m = {}, k = {}, r = null, q = 0; q < d.length; q++) {
            var p = d[q],
                r = null === r ? b / t[p] : t[r] / t[p],
                u = a / t[p],
                v = c / t[p];
            e && ((u = 0 < u ? Math.floor(u) : Math.ceil(u)), (v = 0 < v ? Math.floor(v) : Math.ceil(v)));
            "Days" !== p && ((u %= r), (v %= r));
            h[p] = u;
            f[p] = Math.abs(u);
            l[p] = v;
            k[p] = Math.abs(v);
            g[p] = Math.abs(u) / r;
            m[p] = Math.abs(v) / r;
            r = p;
        }
        return { raw_time: h, raw_old_time: l, time: f, old_time: k, pct: g, old_pct: m };
    }
    function C(a) {
        for (var c = ["webkit", "moz"], b = 0; b < c.length && !a.requestAnimationFrame; ++b) (a.requestAnimationFrame = a[c[b] + "RequestAnimationFrame"]), (a.cancelAnimationFrame = a[c[b] + "CancelAnimationFrame"]);
        (a.requestAnimationFrame && a.cancelAnimationFrame) ||
            ((a.requestAnimationFrame = function (c, b, h) {
                "undefined" === typeof h && (h = { data: { last_frame: 0 } });
                var l = new Date().getTime(),
                    f = Math.max(0, 16 - (l - h.data.last_frame));
                b = a.setTimeout(function () {
                    c(l + f);
                }, f);
                h.data.last_frame = l + f;
                return b;
            }),
            (a.cancelAnimationFrame = function (a) {
                clearTimeout(a);
            }));
    }
    var m = window;
    Object.keys ||
        (Object.keys = (function () {
            var a = Object.prototype.hasOwnProperty,
                c = !{ toString: null }.propertyIsEnumerable("toString"),
                b = "toString toLocaleString valueOf hasOwnProperty isPrototypeOf propertyIsEnumerable constructor".split(" "),
                d = b.length;
            return function (e) {
                if ("object" !== typeof e && ("function" !== typeof e || null === e)) throw new TypeError("Object.keys called on non-object");
                var h = [],
                    l;
                for (l in e) a.call(e, l) && h.push(l);
                if (c) for (l = 0; l < d; l++) a.call(e, b[l]) && h.push(b[l]);
                return h;
            };
        })());
    var x = !1,
        y = ["Days", "Hours", "Minutes", "Seconds"],
        D = { Seconds: "Minutes", Minutes: "Hours", Hours: "Days", Days: "Years" },
        t = { Seconds: 1, Minutes: 60, Hours: 3600, Days: 86400, Months: 2678400, Years: 31536e3 };
    Array.prototype.indexOf ||
        (Array.prototype.indexOf = function (a, c) {
            var b = this.length >>> 0,
                d = Number(c) || 0,
                d = 0 > d ? Math.ceil(d) : Math.floor(d);
            for (0 > d && (d += b); d < b; d++) if (d in this && this[d] === a) return d;
            return -1;
        });
    var w = {},
        g = function (a, c) {
            this.element = a;
            this.container;
            this.listeners = null;
            this.data = {
                paused: !1,
                last_frame: 0,
                animation_frame: null,
                interval_fallback: null,
                timer: !1,
                total_duration: null,
                prev_time: null,
                drawn_units: [],
                text_elements: { Days: null, Hours: null, Minutes: null, Seconds: null },
                attributes: { canvas: null, context: null, item_size: null, line_width: null, radius: null, outer_radius: null },
                state: { fading: { Days: !1, Hours: !1, Minutes: !1, Seconds: !1 } },
            };
            this.config = null;
            this.setOptions(c);
            this.initialize();
        };
    g.prototype.clearListeners = function () {
        this.listeners = { all: [], visible: [] };
    };
    g.prototype.addTime = function (a) {
        if (this.data.attributes.ref_date instanceof Date) {
            var c = this.data.attributes.ref_date;
            c.setSeconds(c.getSeconds() + a);
        } else isNaN(this.data.attributes.ref_date) || (this.data.attributes.ref_date += 1e3 * a);
    };
    g.prototype.initialize = function (a) {
        this.data.drawn_units = [];
        for (var c = 0; c < Object.keys(this.config.time).length; c++) {
            var b = Object.keys(this.config.time)[c];
            this.config.time[b].show && this.data.drawn_units.push(b);
        }
        f(this.element).children("div.time_circles").remove();
        "undefined" === typeof a && (a = !0);
        (a || null === this.listeners) && this.clearListeners();
        this.container = f("<div>");
        this.container.addClass("time_circles");
        this.container.appendTo(this.element);
        c = this.element.offsetHeight;
        a = this.element.offsetWidth;
        0 === c && (c = f(this.element).height());
        0 === a && (a = f(this.element).width());
        0 === c && 0 < a ? (c = a / this.data.drawn_units.length) : 0 === a && 0 < c && (a = c * this.data.drawn_units.length);
        b = document.createElement("canvas");
        b.width = a;
        b.height = c;
        this.data.attributes.canvas = f(b);
        this.data.attributes.canvas.appendTo(this.container);
        var d = B();
        d || "undefined" === typeof G_vmlCanvasManager || (G_vmlCanvasManager.initElement(b), (d = x = !0));
        d && (this.data.attributes.context = b.getContext("2d"));
        this.data.attributes.item_size = Math.min(a / this.data.drawn_units.length, c);
        this.data.attributes.line_width = this.data.attributes.item_size * this.config.fg_width;
        this.data.attributes.radius = (0.8 * this.data.attributes.item_size - this.data.attributes.line_width) / 2;
        this.data.attributes.outer_radius = this.data.attributes.radius + 0.5 * Math.max(this.data.attributes.line_width, this.data.attributes.line_width * this.config.bg_width);
        var c = 0,
            e;
        for (e in this.data.text_elements)
            this.config.time[e].show &&
                ((a = f("<div>")),
                a.addClass("textDiv_" + e),
                a.css("top", Math.round(0.35 * this.data.attributes.item_size)),
                a.css("left", Math.round(c++ * this.data.attributes.item_size)),
                a.css("width", this.data.attributes.item_size),
                a.appendTo(this.container),
                (b = f("<h4>")),
                b.text(this.config.time[e].text),
                b.css("font-size", Math.round(this.config.text_size * this.data.attributes.item_size)),
                b.css("line-height", Math.round(this.config.text_size * this.data.attributes.item_size) + "px"),
                b.appendTo(a),
                (b = f("<span>")),
                b.css("font-size", Math.round(3 * this.config.text_size * this.data.attributes.item_size)),
                b.css("line-height", Math.round(this.config.text_size * this.data.attributes.item_size) + "px"),
                b.appendTo(a),
                (this.data.text_elements[e] = b));
        this.start();
        this.config.start || (this.data.paused = !0);
        var h = this;
        this.data.interval_fallback = m.setInterval(function () {
            h.update.call(h, !0);
        }, 100);
    };
    g.prototype.update = function (a) {
        if ("undefined" === typeof a) a = !1;
        else if (a && this.data.paused) return;
        x && this.data.attributes.context.clearRect(0, 0, this.data.attributes.canvas[0].width, this.data.attributes.canvas[0].hright);
        var c,
            b,
            d = this.data.prev_time;
        c = new Date();
        this.data.prev_time = c;
        null === d && (d = c);
        if (!this.config.count_past_zero && c > this.data.attributes.ref_date) {
            for (b = 0; b < this.data.drawn_units.length; b++) {
                var e = this.data.drawn_units[b];
                this.data.text_elements[e].text("0");
                var h = b * this.data.attributes.item_size + this.data.attributes.item_size / 2,
                    l = this.data.attributes.item_size / 2,
                    f = this.config.time[e].color;
                this.drawArc(h, l, f, 0);
            }
            this.stop();
        } else {
            c = (this.data.attributes.ref_date - c) / 1e3;
            b = (this.data.attributes.ref_date - d) / 1e3;
            var e = "smooth" !== this.config.animation,
                d = z(c, b, this.data.total_duration, this.data.drawn_units, e),
                g = z(c, b, t.Years, y, e),
                k = (b = 0),
                n = null,
                r = this.data.drawn_units.slice();
            for (b in y)
                (e = y[b]),
                    Math.floor(g.raw_time[e]) !== Math.floor(g.raw_old_time[e]) && this.notifyListeners(e, Math.floor(g.time[e]), Math.floor(c), "all"),
                    0 > r.indexOf(e) ||
                        (Math.floor(d.raw_time[e]) !== Math.floor(d.raw_old_time[e]) && this.notifyListeners(e, Math.floor(d.time[e]), Math.floor(c), "visible"),
                        a ||
                            (this.data.text_elements[e].text(Math.floor(Math.abs(d.time[e]))),
                            (h = k * this.data.attributes.item_size + this.data.attributes.item_size / 2),
                            (l = this.data.attributes.item_size / 2),
                            (f = this.config.time[e].color),
                            "smooth" === this.config.animation
                                ? (null === n ||
                                      x ||
                                      (Math.floor(d.time[n]) > Math.floor(d.old_time[n])
                                          ? (this.radialFade(h, l, f, 1, e), (this.data.state.fading[e] = !0))
                                          : Math.floor(d.time[n]) < Math.floor(d.old_time[n]) && (this.radialFade(h, l, f, 0, e), (this.data.state.fading[e] = !0))),
                                  this.data.state.fading[e] || this.drawArc(h, l, f, d.pct[e]))
                                : this.animateArc(h, l, f, d.pct[e], d.old_pct[e], new Date().getTime() + 200)),
                        (n = e),
                        k++);
            if (!this.data.paused && !a) {
                var q = this,
                    p = function () {
                        q.update.call(q);
                    };
                "smooth" === this.config.animation
                    ? (this.data.animation_frame = m.requestAnimationFrame(p, q.element, q))
                    : ((a = (c % 1) * 1e3),
                      0 > a && (a = 1e3 + a),
                      (q.data.animation_frame = m.setTimeout(function () {
                          q.data.animation_frame = m.requestAnimationFrame(p, q.element, q);
                      }, a + 50)));
            }
        }
    };
    g.prototype.animateArc = function (a, c, b, d, e, h) {
        if (null !== this.data.attributes.context)
            if (0.5 < Math.abs(e - d)) 0 === d ? this.radialFade(a, c, b, 1) : this.radialFade(a, c, b, 0);
            else {
                var f = (200 - (h - new Date().getTime())) / 200;
                1 < f && (f = 1);
                this.drawArc(a, c, b, e * (1 - f) + d * f);
                if (!(1 <= f)) {
                    var g = this;
                    m.requestAnimationFrame(function () {
                        g.animateArc(a, c, b, d, e, h);
                    }, this.element);
                }
            }
    };
    g.prototype.drawArc = function (a, c, b, d) {
        if (null !== this.data.attributes.context) {
            var e = Math.max(this.data.attributes.outer_radius, this.data.attributes.item_size / 2);
            x || this.data.attributes.context.clearRect(a - e, c - e, 2 * e, 2 * e);
            this.config.use_background &&
                (this.data.attributes.context.beginPath(),
                this.data.attributes.context.arc(a, c, this.data.attributes.radius, 0, 2 * Math.PI, !1),
                (this.data.attributes.context.lineWidth = this.data.attributes.line_width * this.config.bg_width),
                (this.data.attributes.context.strokeStyle = this.config.circle_bg_color),
                this.data.attributes.context.stroke());
            var f,
                e = -0.5 * Math.PI + (this.config.start_angle / 360) * 2 * Math.PI;
            f = 2 * d * Math.PI;
            "Both" === this.config.direction ? ((d = !1), (e -= f / 2), (f = e + f)) : "Clockwise" === this.config.direction ? ((d = !1), (f = e + f)) : ((d = !0), (f = e - f));
            this.data.attributes.context.beginPath();
            this.data.attributes.context.arc(a, c, this.data.attributes.radius, e, f, d);
            this.data.attributes.context.lineWidth = this.data.attributes.line_width;
            this.data.attributes.context.strokeStyle = b;
            this.data.attributes.context.stroke();
        }
    };
    g.prototype.radialFade = function (a, c, b, d, e) {
        var f = A(b),
            g = this;
        b = 0.2 * (1 === d ? -1 : 1);
        var k;
        for (k = 0; 1 >= d && 0 <= d; k++)
            (function () {
                var b = "rgba(" + f.r + ", " + f.g + ", " + f.b + ", " + Math.round(10 * d) / 10 + ")";
                m.setTimeout(function () {
                    g.drawArc(a, c, b, 1);
                }, 50 * k);
            })(),
                (d += b);
        m.setTimeout(function () {
            g.data.state.fading[e] = !1;
        }, 50 * k);
    };
    g.prototype.timeLeft = function () {
        return this.data.paused && "number" === typeof this.data.timer ? this.data.timer : (this.data.attributes.ref_date - new Date()) / 1e3;
    };
    g.prototype.start = function () {
        m.cancelAnimationFrame(this.data.animation_frame);
        m.clearTimeout(this.data.animation_frame);
        var a = f(this.element).data("date");
        "undefined" === typeof a && (a = f(this.element).attr("data-date"));
        if ("string" === typeof a) {
            var c = this.data.attributes;
            var b = a.match(/^[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{1,2}:[0-9]{2}:[0-9]{2}$/);
            null !== b && 0 < b.length
                ? ((b = a.split(" ")), (a = b[0].split("-")), (b = b[1].split(":")), (a = new Date(a[0], a[1] - 1, a[2], b[0], b[1], b[2])))
                : ((b = Date.parse(a)), isNaN(b) ? ((b = Date.parse(a.replace(/-/g, "/").replace("T", " "))), (a = isNaN(b) ? new Date() : b)) : (a = b));
            c.ref_date = a;
        } else
            "number" === typeof this.data.timer
                ? this.data.paused && (this.data.attributes.ref_date = new Date().getTime() + 1e3 * this.data.timer)
                : ((c = f(this.element).data("timer")),
                  "undefined" === typeof c && (c = f(this.element).attr("data-timer")),
                  "string" === typeof c && (c = parseFloat(c)),
                  "number" === typeof c ? ((this.data.timer = c), (this.data.attributes.ref_date = new Date().getTime() + 1e3 * c)) : (this.data.attributes.ref_date = this.config.ref_date));
        this.data.paused = !1;
        this.update.call(this);
    };
    g.prototype.restart = function () {
        this.data.timer = !1;
        this.start();
    };
    g.prototype.stop = function () {
        "number" === typeof this.data.timer && (this.data.timer = this.timeLeft(this));
        this.data.paused = !0;
        m.cancelAnimationFrame(this.data.animation_frame);
    };
    g.prototype.destroy = function () {
        this.clearListeners();
        this.stop();
        m.clearInterval(this.data.interval_fallback);
        this.data.interval_fallback = null;
        this.container.remove();
        f(this.element).removeAttr("data-tc-id");
        f(this.element).removeData("tc-id");
    };
    g.prototype.setOptions = function (a) {
        null === this.config && ((this.default_options.ref_date = new Date()), (this.config = f.extend(!0, {}, this.default_options)));
        f.extend(!0, this.config, a);
        m = this.config.use_top_frame ? window.top : window;
        "undefined" !== typeof m.TC_Instance_List ? (w = m.TC_Instance_List) : (m.TC_Instance_List = w);
        C(m);
        this.data.total_duration = this.config.total_duration;
        if ("string" === typeof this.data.total_duration)
            if ("undefined" !== typeof t[this.data.total_duration]) this.data.total_duration = t[this.data.total_duration];
            else if ("Auto" === this.data.total_duration)
                for (a = 0; a < Object.keys(this.config.time).length; a++) {
                    var c = Object.keys(this.config.time)[a];
                    if (this.config.time[c].show) {
                        this.data.total_duration = t[D[c]];
                        break;
                    }
                }
            else (this.data.total_duration = t.Years), console.error("Valid values for TimeCircles config.total_duration are either numeric, or (string) Years, Months, Days, Hours, Minutes, Auto");
    };
    g.prototype.addListener = function (a, c, b) {
        "function" === typeof a && ("undefined" === typeof b && (b = "visible"), this.listeners[b].push({ func: a, scope: c }));
    };
    g.prototype.notifyListeners = function (a, c, b, d) {
        for (var e = 0; e < this.listeners[d].length; e++) {
            var f = this.listeners[d][e];
            f.func.apply(f.scope, [a, c, b]);
        }
    };
    g.prototype.default_options = {
        ref_date: new Date(),
        start: !0,
        animation: "smooth",
        count_past_zero: !0,
        circle_bg_color: "#60686F",
        use_background: !0,
        fg_width: 0.1,
        bg_width: 1.2,
        text_size: 0.07,
        total_duration: "Auto",
        direction: "Clockwise",
        use_top_frame: !1,
        start_angle: 0,
        time: { Days: { show: !0, text: "Days", color: "#FC6" }, Hours: { show: !0, text: "Hours", color: "#9CF" }, Minutes: { show: !0, text: "Minutes", color: "#BFB" }, Seconds: { show: !0, text: "Seconds", color: "#F99" } },
    };
    var k = function (a, c) {
        this.elements = a;
        this.options = c;
        this.foreach();
    };
    k.prototype.getInstance = function (a) {
        var c = f(a).data("tc-id");
        "undefined" === typeof c && ((c = n() + n() + "-" + n() + "-" + n() + "-" + n() + "-" + n() + n() + n()), f(a).attr("data-tc-id", c));
        if ("undefined" === typeof w[c]) {
            var b = this.options,
                d = f(a).data("options");
            "string" === typeof d && (d = JSON.parse(d));
            "object" === typeof d && (b = f.extend(!0, {}, this.options, d));
            a = new g(a, b);
            w[c] = a;
        } else (a = w[c]), "undefined" !== typeof this.options && a.setOptions(this.options);
        return a;
    };
    k.prototype.addTime = function (a) {
        this.foreach(function (c) {
            c.addTime(a);
        });
    };
    k.prototype.foreach = function (a) {
        var c = this;
        this.elements.each(function () {
            var b = c.getInstance(this);
            "function" === typeof a && a(b);
        });
        return this;
    };
    k.prototype.start = function () {
        this.foreach(function (a) {
            a.start();
        });
        return this;
    };
    k.prototype.stop = function () {
        this.foreach(function (a) {
            a.stop();
        });
        return this;
    };
    k.prototype.restart = function () {
        this.foreach(function (a) {
            a.restart();
        });
        return this;
    };
    k.prototype.rebuild = function () {
        this.foreach(function (a) {
            a.initialize(!1);
        });
        return this;
    };
    k.prototype.getTime = function () {
        return this.getInstance(this.elements[0]).timeLeft();
    };
    k.prototype.addListener = function (a, c) {
        "undefined" === typeof c && (c = "visible");
        var b = this;
        this.foreach(function (d) {
            d.addListener(a, b.elements, c);
        });
        return this;
    };
    k.prototype.destroy = function () {
        this.foreach(function (a) {
            a.destroy();
        });
        return this;
    };
    k.prototype.end = function () {
        return this.elements;
    };
    f.fn.TimeCircles = function (a) {
        return new k(this, a);
    };
  })(jQuery);