/*!
 * jQuery JavaScript Library v1.11.2
 * http://jquery.com/
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 *
 * Copyright 2005, 2014 jQuery Foundation, Inc. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Date: 2014-12-17T15:27Z
 */
!function (e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document)
            throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function (e, t) {
    function n(e) {
        var t = e.length, n = re.type(e);
        return"function" === n || re.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e
    }
    function i(e, t, n) {
        if (re.isFunction(t))
            return re.grep(e, function (e, i) {
                return!!t.call(e, i, e) !== n
            });
        if (t.nodeType)
            return re.grep(e, function (e) {
                return e === t !== n
            });
        if ("string" == typeof t) {
            if (pe.test(t))
                return re.filter(t, e, n);
            t = re.filter(t, e)
        }
        return re.grep(e, function (e) {
            return re.inArray(e, t) >= 0 !== n
        })
    }
    function r(e, t) {
        do
            e = e[t];
        while (e && 1 !== e.nodeType);
        return e
    }
    function o(e) {
        var t = be[e] = {};
        return re.each(e.match(xe) || [], function (e, n) {
            t[n] = !0
        }), t
    }
    function s() {
        he.addEventListener ? (he.removeEventListener("DOMContentLoaded", a, !1), e.removeEventListener("load", a, !1)) : (he.detachEvent("onreadystatechange", a), e.detachEvent("onload", a))
    }
    function a() {
        (he.addEventListener || "load" === event.type || "complete" === he.readyState) && (s(), re.ready())
    }
    function l(e, t, n) {
        if (void 0 === n && 1 === e.nodeType) {
            var i = "data-" + t.replace(Ce, "-$1").toLowerCase();
            if (n = e.getAttribute(i), "string" == typeof n) {
                try {
                    n = "true" === n ? !0 : "false" === n ? !1 : "null" === n ? null : +n + "" === n ? +n : Ee.test(n) ? re.parseJSON(n) : n
                } catch (r) {
                }
                re.data(e, t, n)
            } else
                n = void 0
        }
        return n
    }
    function c(e) {
        var t;
        for (t in e)
            if (("data" !== t || !re.isEmptyObject(e[t])) && "toJSON" !== t)
                return!1;
        return!0
    }
    function u(e, t, n, i) {
        if (re.acceptData(e)) {
            var r, o, s = re.expando, a = e.nodeType, l = a ? re.cache : e, c = a ? e[s] : e[s] && s;
            if (c && l[c] && (i || l[c].data) || void 0 !== n || "string" != typeof t)
                return c || (c = a ? e[s] = U.pop() || re.guid++ : s), l[c] || (l[c] = a ? {} : {toJSON: re.noop}), ("object" == typeof t || "function" == typeof t) && (i ? l[c] = re.extend(l[c], t) : l[c].data = re.extend(l[c].data, t)), o = l[c], i || (o.data || (o.data = {}), o = o.data), void 0 !== n && (o[re.camelCase(t)] = n), "string" == typeof t ? (r = o[t], null == r && (r = o[re.camelCase(t)])) : r = o, r
        }
    }
    function d(e, t, n) {
        if (re.acceptData(e)) {
            var i, r, o = e.nodeType, s = o ? re.cache : e, a = o ? e[re.expando] : re.expando;
            if (s[a]) {
                if (t && (i = n ? s[a] : s[a].data)) {
                    re.isArray(t) ? t = t.concat(re.map(t, re.camelCase)) : t in i ? t = [t] : (t = re.camelCase(t), t = t in i ? [t] : t.split(" ")), r = t.length;
                    for (; r--; )
                        delete i[t[r]];
                    if (n ? !c(i) : !re.isEmptyObject(i))
                        return
                }
                (n || (delete s[a].data, c(s[a]))) && (o ? re.cleanData([e], !0) : ne.deleteExpando || s != s.window ? delete s[a] : s[a] = null)
            }
        }
    }
    function p() {
        return!0
    }
    function f() {
        return!1
    }
    function h() {
        try {
            return he.activeElement
        } catch (e) {
        }
    }
    function g(e) {
        var t = He.split("|"), n = e.createDocumentFragment();
        if (n.createElement)
            for (; t.length; )
                n.createElement(t.pop());
        return n
    }
    function m(e, t) {
        var n, i, r = 0, o = typeof e.getElementsByTagName !== Te ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll !== Te ? e.querySelectorAll(t || "*") : void 0;
        if (!o)
            for (o = [], n = e.childNodes || e; null != (i = n[r]); r++)
                !t || re.nodeName(i, t) ? o.push(i) : re.merge(o, m(i, t));
        return void 0 === t || t && re.nodeName(e, t) ? re.merge([e], o) : o
    }
    function v(e) {
        We.test(e.type) && (e.defaultChecked = e.checked)
    }
    function y(e, t) {
        return re.nodeName(e, "table") && re.nodeName(11 !== t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }
    function x(e) {
        return e.type = (null !== re.find.attr(e, "type")) + "/" + e.type, e
    }
    function b(e) {
        var t = Qe.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }
    function w(e, t) {
        for (var n, i = 0; null != (n = e[i]); i++)
            re._data(n, "globalEval", !t || re._data(t[i], "globalEval"))
    }
    function S(e, t) {
        if (1 === t.nodeType && re.hasData(e)) {
            var n, i, r, o = re._data(e), s = re._data(t, o), a = o.events;
            if (a) {
                delete s.handle, s.events = {};
                for (n in a)
                    for (i = 0, r = a[n].length; r > i; i++)
                        re.event.add(t, n, a[n][i])
            }
            s.data && (s.data = re.extend({}, s.data))
        }
    }
    function T(e, t) {
        var n, i, r;
        if (1 === t.nodeType) {
            if (n = t.nodeName.toLowerCase(), !ne.noCloneEvent && t[re.expando]) {
                r = re._data(t);
                for (i in r.events)
                    re.removeEvent(t, i, r.handle);
                t.removeAttribute(re.expando)
            }
            "script" === n && t.text !== e.text ? (x(t).text = e.text, b(t)) : "object" === n ? (t.parentNode && (t.outerHTML = e.outerHTML), ne.html5Clone && e.innerHTML && !re.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)) : "input" === n && We.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : "option" === n ? t.defaultSelected = t.selected = e.defaultSelected : ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
        }
    }
    function E(t, n) {
        var i, r = re(n.createElement(t)).appendTo(n.body), o = e.getDefaultComputedStyle && (i = e.getDefaultComputedStyle(r[0])) ? i.display : re.css(r[0], "display");
        return r.detach(), o
    }
    function C(e) {
        var t = he, n = Ze[e];
        return n || (n = E(e, t), "none" !== n && n || (Ke = (Ke || re("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement), t = (Ke[0].contentWindow || Ke[0].contentDocument).document, t.write(), t.close(), n = E(e, t), Ke.detach()), Ze[e] = n), n
    }
    function k(e, t) {
        return{get: function () {
                var n = e();
                if (null != n)
                    return n ? void delete this.get : (this.get = t).apply(this, arguments)
            }}
    }
    function N(e, t) {
        if (t in e)
            return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), i = t, r = pt.length; r--; )
            if (t = pt[r] + n, t in e)
                return t;
        return i
    }
    function $(e, t) {
        for (var n, i, r, o = [], s = 0, a = e.length; a > s; s++)
            i = e[s], i.style && (o[s] = re._data(i, "olddisplay"), n = i.style.display, t ? (o[s] || "none" !== n || (i.style.display = ""), "" === i.style.display && $e(i) && (o[s] = re._data(i, "olddisplay", C(i.nodeName)))) : (r = $e(i), (n && "none" !== n || !r) && re._data(i, "olddisplay", r ? n : re.css(i, "display"))));
        for (s = 0; a > s; s++)
            i = e[s], i.style && (t && "none" !== i.style.display && "" !== i.style.display || (i.style.display = t ? o[s] || "" : "none"));
        return e
    }
    function A(e, t, n) {
        var i = lt.exec(t);
        return i ? Math.max(0, i[1] - (n || 0)) + (i[2] || "px") : t
    }
    function W(e, t, n, i, r) {
        for (var o = n === (i ? "border" : "content") ? 4 : "width" === t ? 1 : 0, s = 0; 4 > o; o += 2)
            "margin" === n && (s += re.css(e, n + Ne[o], !0, r)), i ? ("content" === n && (s -= re.css(e, "padding" + Ne[o], !0, r)), "margin" !== n && (s -= re.css(e, "border" + Ne[o] + "Width", !0, r))) : (s += re.css(e, "padding" + Ne[o], !0, r), "padding" !== n && (s += re.css(e, "border" + Ne[o] + "Width", !0, r)));
        return s
    }
    function _(e, t, n) {
        var i = !0, r = "width" === t ? e.offsetWidth : e.offsetHeight, o = et(e), s = ne.boxSizing && "border-box" === re.css(e, "boxSizing", !1, o);
        if (0 >= r || null == r) {
            if (r = tt(e, t, o), (0 > r || null == r) && (r = e.style[t]), it.test(r))
                return r;
            i = s && (ne.boxSizingReliable() || r === e.style[t]), r = parseFloat(r) || 0
        }
        return r + W(e, t, n || (s ? "border" : "content"), i, o) + "px"
    }
    function D(e, t, n, i, r) {
        return new D.prototype.init(e, t, n, i, r)
    }
    function j() {
        return setTimeout(function () {
            ft = void 0
        }), ft = re.now()
    }
    function L(e, t) {
        var n, i = {height: e}, r = 0;
        for (t = t?1:0; 4 > r; r += 2 - t)
            n = Ne[r], i["margin" + n] = i["padding" + n] = e;
        return t && (i.opacity = i.width = e), i
    }
    function M(e, t, n) {
        for (var i, r = (xt[t] || []).concat(xt["*"]), o = 0, s = r.length; s > o; o++)
            if (i = r[o].call(n, t, e))
                return i
    }
    function H(e, t, n) {
        var i, r, o, s, a, l, c, u, d = this, p = {}, f = e.style, h = e.nodeType && $e(e), g = re._data(e, "fxshow");
        n.queue || (a = re._queueHooks(e, "fx"), null == a.unqueued && (a.unqueued = 0, l = a.empty.fire, a.empty.fire = function () {
            a.unqueued || l()
        }), a.unqueued++, d.always(function () {
            d.always(function () {
                a.unqueued--, re.queue(e, "fx").length || a.empty.fire()
            })
        })), 1 === e.nodeType && ("height"in t || "width"in t) && (n.overflow = [f.overflow, f.overflowX, f.overflowY], c = re.css(e, "display"), u = "none" === c ? re._data(e, "olddisplay") || C(e.nodeName) : c, "inline" === u && "none" === re.css(e, "float") && (ne.inlineBlockNeedsLayout && "inline" !== C(e.nodeName) ? f.zoom = 1 : f.display = "inline-block")), n.overflow && (f.overflow = "hidden", ne.shrinkWrapBlocks() || d.always(function () {
            f.overflow = n.overflow[0], f.overflowX = n.overflow[1], f.overflowY = n.overflow[2]
        }));
        for (i in t)
            if (r = t[i], gt.exec(r)) {
                if (delete t[i], o = o || "toggle" === r, r === (h ? "hide" : "show")) {
                    if ("show" !== r || !g || void 0 === g[i])
                        continue;
                    h = !0
                }
                p[i] = g && g[i] || re.style(e, i)
            } else
                c = void 0;
        if (re.isEmptyObject(p))
            "inline" === ("none" === c ? C(e.nodeName) : c) && (f.display = c);
        else {
            g ? "hidden"in g && (h = g.hidden) : g = re._data(e, "fxshow", {}), o && (g.hidden = !h), h ? re(e).show() : d.done(function () {
                re(e).hide()
            }), d.done(function () {
                var t;
                re._removeData(e, "fxshow");
                for (t in p)
                    re.style(e, t, p[t])
            });
            for (i in p)
                s = M(h ? g[i] : 0, i, d), i in g || (g[i] = s.start, h && (s.end = s.start, s.start = "width" === i || "height" === i ? 1 : 0))
        }
    }
    function q(e, t) {
        var n, i, r, o, s;
        for (n in e)
            if (i = re.camelCase(n), r = t[i], o = e[n], re.isArray(o) && (r = o[1], o = e[n] = o[0]), n !== i && (e[i] = o, delete e[n]), s = re.cssHooks[i], s && "expand"in s) {
                o = s.expand(o), delete e[i];
                for (n in o)
                    n in e || (e[n] = o[n], t[n] = r)
            } else
                t[i] = r
    }
    function F(e, t, n) {
        var i, r, o = 0, s = yt.length, a = re.Deferred().always(function () {
            delete l.elem
        }), l = function () {
            if (r)
                return!1;
            for (var t = ft || j(), n = Math.max(0, c.startTime + c.duration - t), i = n / c.duration || 0, o = 1 - i, s = 0, l = c.tweens.length; l > s; s++)
                c.tweens[s].run(o);
            return a.notifyWith(e, [c, o, n]), 1 > o && l ? n : (a.resolveWith(e, [c]), !1)
        }, c = a.promise({elem: e, props: re.extend({}, t), opts: re.extend(!0, {specialEasing: {}}, n), originalProperties: t, originalOptions: n, startTime: ft || j(), duration: n.duration, tweens: [], createTween: function (t, n) {
                var i = re.Tween(e, c.opts, t, n, c.opts.specialEasing[t] || c.opts.easing);
                return c.tweens.push(i), i
            }, stop: function (t) {
                var n = 0, i = t ? c.tweens.length : 0;
                if (r)
                    return this;
                for (r = !0; i > n; n++)
                    c.tweens[n].run(1);
                return t ? a.resolveWith(e, [c, t]) : a.rejectWith(e, [c, t]), this
            }}), u = c.props;
        for (q(u, c.opts.specialEasing); s > o; o++)
            if (i = yt[o].call(c, e, u, c.opts))
                return i;
        return re.map(u, M, c), re.isFunction(c.opts.start) && c.opts.start.call(e, c), re.fx.timer(re.extend(l, {elem: e, anim: c, queue: c.opts.queue})), c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always)
    }
    function O(e) {
        return function (t, n) {
            "string" != typeof t && (n = t, t = "*");
            var i, r = 0, o = t.toLowerCase().match(xe) || [];
            if (re.isFunction(n))
                for (; i = o[r++]; )
                    "+" === i.charAt(0) ? (i = i.slice(1) || "*", (e[i] = e[i] || []).unshift(n)) : (e[i] = e[i] || []).push(n)
        }
    }
    function P(e, t, n, i) {
        function r(a) {
            var l;
            return o[a] = !0, re.each(e[a] || [], function (e, a) {
                var c = a(t, n, i);
                return"string" != typeof c || s || o[c] ? s ? !(l = c) : void 0 : (t.dataTypes.unshift(c), r(c), !1)
            }), l
        }
        var o = {}, s = e === Bt;
        return r(t.dataTypes[0]) || !o["*"] && r("*")
    }
    function I(e, t) {
        var n, i, r = re.ajaxSettings.flatOptions || {};
        for (i in t)
            void 0 !== t[i] && ((r[i] ? e : n || (n = {}))[i] = t[i]);
        return n && re.extend(!0, e, n), e
    }
    function z(e, t, n) {
        for (var i, r, o, s, a = e.contents, l = e.dataTypes; "*" === l[0]; )
            l.shift(), void 0 === r && (r = e.mimeType || t.getResponseHeader("Content-Type"));
        if (r)
            for (s in a)
                if (a[s] && a[s].test(r)) {
                    l.unshift(s);
                    break
                }
        if (l[0]in n)
            o = l[0];
        else {
            for (s in n) {
                if (!l[0] || e.converters[s + " " + l[0]]) {
                    o = s;
                    break
                }
                i || (i = s)
            }
            o = o || i
        }
        return o ? (o !== l[0] && l.unshift(o), n[o]) : void 0
    }
    function B(e, t, n, i) {
        var r, o, s, a, l, c = {}, u = e.dataTypes.slice();
        if (u[1])
            for (s in e.converters)
                c[s.toLowerCase()] = e.converters[s];
        for (o = u.shift(); o; )
            if (e.responseFields[o] && (n[e.responseFields[o]] = t), !l && i && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = o, o = u.shift())
                if ("*" === o)
                    o = l;
                else if ("*" !== l && l !== o) {
                    if (s = c[l + " " + o] || c["* " + o], !s)
                        for (r in c)
                            if (a = r.split(" "), a[1] === o && (s = c[l + " " + a[0]] || c["* " + a[0]])) {
                                s === !0 ? s = c[r] : c[r] !== !0 && (o = a[0], u.unshift(a[1]));
                                break
                            }
                    if (s !== !0)
                        if (s && e["throws"])
                            t = s(t);
                        else
                            try {
                                t = s(t)
                            } catch (d) {
                                return{state: "parsererror", error: s ? d : "No conversion from " + l + " to " + o}
                            }
                }
        return{state: "success", data: t}
    }
    function R(e, t, n, i) {
        var r;
        if (re.isArray(t))
            re.each(t, function (t, r) {
                n || Qt.test(e) ? i(e, r) : R(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
            });
        else if (n || "object" !== re.type(t))
            i(e, t);
        else
            for (r in t)
                R(e + "[" + r + "]", t[r], n, i)
    }
    function X() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {
        }
    }
    function V() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {
        }
    }
    function Q(e) {
        return re.isWindow(e) ? e : 9 === e.nodeType ? e.defaultView || e.parentWindow : !1
    }
    var U = [], Y = U.slice, J = U.concat, G = U.push, K = U.indexOf, Z = {}, ee = Z.toString, te = Z.hasOwnProperty, ne = {}, ie = "1.11.2", re = function (e, t) {
        return new re.fn.init(e, t)
    }, oe = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, se = /^-ms-/, ae = /-([\da-z])/gi, le = function (e, t) {
        return t.toUpperCase()
    };
    re.fn = re.prototype = {jquery: ie, constructor: re, selector: "", length: 0, toArray: function () {
            return Y.call(this)
        }, get: function (e) {
            return null != e ? 0 > e ? this[e + this.length] : this[e] : Y.call(this)
        }, pushStack: function (e) {
            var t = re.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        }, each: function (e, t) {
            return re.each(this, e, t)
        }, map: function (e) {
            return this.pushStack(re.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        }, slice: function () {
            return this.pushStack(Y.apply(this, arguments))
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, eq: function (e) {
            var t = this.length, n = +e + (0 > e ? t : 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        }, end: function () {
            return this.prevObject || this.constructor(null)
        }, push: G, sort: U.sort, splice: U.splice}, re.extend = re.fn.extend = function () {
        var e, t, n, i, r, o, s = arguments[0] || {}, a = 1, l = arguments.length, c = !1;
        for ("boolean" == typeof s && (c = s, s = arguments[a] || {}, a++), "object" == typeof s || re.isFunction(s) || (s = {}), a === l && (s = this, a--); l > a; a++)
            if (null != (r = arguments[a]))
                for (i in r)
                    e = s[i], n = r[i], s !== n && (c && n && (re.isPlainObject(n) || (t = re.isArray(n))) ? (t ? (t = !1, o = e && re.isArray(e) ? e : []) : o = e && re.isPlainObject(e) ? e : {}, s[i] = re.extend(c, o, n)) : void 0 !== n && (s[i] = n));
        return s
    }, re.extend({expando: "jQuery" + (ie + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (e) {
            throw new Error(e)
        }, noop: function () {
        }, isFunction: function (e) {
            return"function" === re.type(e)
        }, isArray: Array.isArray || function (e) {
            return"array" === re.type(e)
        }, isWindow: function (e) {
            return null != e && e == e.window
        }, isNumeric: function (e) {
            return!re.isArray(e) && e - parseFloat(e) + 1 >= 0
        }, isEmptyObject: function (e) {
            var t;
            for (t in e)
                return!1;
            return!0
        }, isPlainObject: function (e) {
            var t;
            if (!e || "object" !== re.type(e) || e.nodeType || re.isWindow(e))
                return!1;
            try {
                if (e.constructor && !te.call(e, "constructor") && !te.call(e.constructor.prototype, "isPrototypeOf"))
                    return!1
            } catch (n) {
                return!1
            }
            if (ne.ownLast)
                for (t in e)
                    return te.call(e, t);
            for (t in e)
                ;
            return void 0 === t || te.call(e, t)
        }, type: function (e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? Z[ee.call(e)] || "object" : typeof e
        }, globalEval: function (t) {
            t && re.trim(t) && (e.execScript || function (t) {
                e.eval.call(e, t)
            })(t)
        }, camelCase: function (e) {
            return e.replace(se, "ms-").replace(ae, le)
        }, nodeName: function (e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        }, each: function (e, t, i) {
            var r, o = 0, s = e.length, a = n(e);
            if (i) {
                if (a)
                    for (; s > o && (r = t.apply(e[o], i), r !== !1); o++)
                        ;
                else
                    for (o in e)
                        if (r = t.apply(e[o], i), r === !1)
                            break
            } else if (a)
                for (; s > o && (r = t.call(e[o], o, e[o]), r !== !1); o++)
                    ;
            else
                for (o in e)
                    if (r = t.call(e[o], o, e[o]), r === !1)
                        break;
            return e
        }, trim: function (e) {
            return null == e ? "" : (e + "").replace(oe, "")
        }, makeArray: function (e, t) {
            var i = t || [];
            return null != e && (n(Object(e)) ? re.merge(i, "string" == typeof e ? [e] : e) : G.call(i, e)), i
        }, inArray: function (e, t, n) {
            var i;
            if (t) {
                if (K)
                    return K.call(t, e, n);
                for (i = t.length, n = n?0 > n?Math.max(0, i + n):n:0; i > n; n++)
                    if (n in t && t[n] === e)
                        return n
            }
            return-1
        }, merge: function (e, t) {
            for (var n = +t.length, i = 0, r = e.length; n > i; )
                e[r++] = t[i++];
            if (n !== n)
                for (; void 0 !== t[i]; )
                    e[r++] = t[i++];
            return e.length = r, e
        }, grep: function (e, t, n) {
            for (var i, r = [], o = 0, s = e.length, a = !n; s > o; o++)
                i = !t(e[o], o), i !== a && r.push(e[o]);
            return r
        }, map: function (e, t, i) {
            var r, o = 0, s = e.length, a = n(e), l = [];
            if (a)
                for (; s > o; o++)
                    r = t(e[o], o, i), null != r && l.push(r);
            else
                for (o in e)
                    r = t(e[o], o, i), null != r && l.push(r);
            return J.apply([], l)
        }, guid: 1, proxy: function (e, t) {
            var n, i, r;
            return"string" == typeof t && (r = e[t], t = e, e = r), re.isFunction(e) ? (n = Y.call(arguments, 2), i = function () {
                return e.apply(t || this, n.concat(Y.call(arguments)))
            }, i.guid = e.guid = e.guid || re.guid++, i) : void 0
        }, now: function () {
            return+new Date
        }, support: ne}), re.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        Z["[object " + t + "]"] = t.toLowerCase()
    });
    var ce = /*!
     * Sizzle CSS Selector Engine v2.2.0-pre
     * http://sizzlejs.com/
     *
     * Copyright 2008, 2014 jQuery Foundation, Inc. and other contributors
     * Released under the MIT license
     * http://jquery.org/license
     *
     * Date: 2014-12-16
     */
            function (e) {
                function t(e, t, n, i) {
                    var r, o, s, a, l, c, d, f, h, g;
                    if ((t ? t.ownerDocument || t : P) !== D && _(t), t = t || D, n = n || [], a = t.nodeType, "string" != typeof e || !e || 1 !== a && 9 !== a && 11 !== a)
                        return n;
                    if (!i && L) {
                        if (11 !== a && (r = ye.exec(e)))
                            if (s = r[1]) {
                                if (9 === a) {
                                    if (o = t.getElementById(s), !o || !o.parentNode)
                                        return n;
                                    if (o.id === s)
                                        return n.push(o), n
                                } else if (t.ownerDocument && (o = t.ownerDocument.getElementById(s)) && F(t, o) && o.id === s)
                                    return n.push(o), n
                            } else {
                                if (r[2])
                                    return K.apply(n, t.getElementsByTagName(e)), n;
                                if ((s = r[3]) && w.getElementsByClassName)
                                    return K.apply(n, t.getElementsByClassName(s)), n
                            }
                        if (w.qsa && (!M || !M.test(e))) {
                            if (f = d = O, h = t, g = 1 !== a && e, 1 === a && "object" !== t.nodeName.toLowerCase()) {
                                for (c = C(e), (d = t.getAttribute("id"))?f = d.replace(be, "\\$&"):t.setAttribute("id", f), f = "[id='" + f + "'] ", l = c.length; l--; )
                                    c[l] = f + p(c[l]);
                                h = xe.test(e) && u(t.parentNode) || t, g = c.join(",")
                            }
                            if (g)
                                try {
                                    return K.apply(n, h.querySelectorAll(g)), n
                                } catch (m) {
                                } finally {
                                    d || t.removeAttribute("id")
                                }
                        }
                    }
                    return N(e.replace(le, "$1"), t, n, i)
                }
                function n() {
                    function e(n, i) {
                        return t.push(n + " ") > S.cacheLength && delete e[t.shift()], e[n + " "] = i
                    }
                    var t = [];
                    return e
                }
                function i(e) {
                    return e[O] = !0, e
                }
                function r(e) {
                    var t = D.createElement("div");
                    try {
                        return!!e(t)
                    } catch (n) {
                        return!1
                    } finally {
                        t.parentNode && t.parentNode.removeChild(t), t = null
                    }
                }
                function o(e, t) {
                    for (var n = e.split("|"), i = e.length; i--; )
                        S.attrHandle[n[i]] = t
                }
                function s(e, t) {
                    var n = t && e, i = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || Q) - (~e.sourceIndex || Q);
                    if (i)
                        return i;
                    if (n)
                        for (; n = n.nextSibling; )
                            if (n === t)
                                return-1;
                    return e ? 1 : -1
                }
                function a(e) {
                    return function (t) {
                        var n = t.nodeName.toLowerCase();
                        return"input" === n && t.type === e
                    }
                }
                function l(e) {
                    return function (t) {
                        var n = t.nodeName.toLowerCase();
                        return("input" === n || "button" === n) && t.type === e
                    }
                }
                function c(e) {
                    return i(function (t) {
                        return t = +t, i(function (n, i) {
                            for (var r, o = e([], n.length, t), s = o.length; s--; )
                                n[r = o[s]] && (n[r] = !(i[r] = n[r]))
                        })
                    })
                }
                function u(e) {
                    return e && "undefined" != typeof e.getElementsByTagName && e
                }
                function d() {
                }
                function p(e) {
                    for (var t = 0, n = e.length, i = ""; n > t; t++)
                        i += e[t].value;
                    return i
                }
                function f(e, t, n) {
                    var i = t.dir, r = n && "parentNode" === i, o = z++;
                    return t.first ? function (t, n, o) {
                        for (; t = t[i]; )
                            if (1 === t.nodeType || r)
                                return e(t, n, o)
                    } : function (t, n, s) {
                        var a, l, c = [I, o];
                        if (s) {
                            for (; t = t[i]; )
                                if ((1 === t.nodeType || r) && e(t, n, s))
                                    return!0
                        } else
                            for (; t = t[i]; )
                                if (1 === t.nodeType || r) {
                                    if (l = t[O] || (t[O] = {}), (a = l[i]) && a[0] === I && a[1] === o)
                                        return c[2] = a[2];
                                    if (l[i] = c, c[2] = e(t, n, s))
                                        return!0
                                }
                    }
                }
                function h(e) {
                    return e.length > 1 ? function (t, n, i) {
                        for (var r = e.length; r--; )
                            if (!e[r](t, n, i))
                                return!1;
                        return!0
                    } : e[0]
                }
                function g(e, n, i) {
                    for (var r = 0, o = n.length; o > r; r++)
                        t(e, n[r], i);
                    return i
                }
                function m(e, t, n, i, r) {
                    for (var o, s = [], a = 0, l = e.length, c = null != t; l > a; a++)
                        (o = e[a]) && (!n || n(o, i, r)) && (s.push(o), c && t.push(a));
                    return s
                }
                function v(e, t, n, r, o, s) {
                    return r && !r[O] && (r = v(r)), o && !o[O] && (o = v(o, s)), i(function (i, s, a, l) {
                        var c, u, d, p = [], f = [], h = s.length, v = i || g(t || "*", a.nodeType ? [a] : a, []), y = !e || !i && t ? v : m(v, p, e, a, l), x = n ? o || (i ? e : h || r) ? [] : s : y;
                        if (n && n(y, x, a, l), r)
                            for (c = m(x, f), r(c, [], a, l), u = c.length; u--; )
                                (d = c[u]) && (x[f[u]] = !(y[f[u]] = d));
                        if (i) {
                            if (o || e) {
                                if (o) {
                                    for (c = [], u = x.length; u--; )
                                        (d = x[u]) && c.push(y[u] = d);
                                    o(null, x = [], c, l)
                                }
                                for (u = x.length; u--; )
                                    (d = x[u]) && (c = o ? ee(i, d) : p[u]) > -1 && (i[c] = !(s[c] = d))
                            }
                        } else
                            x = m(x === s ? x.splice(h, x.length) : x), o ? o(null, s, x, l) : K.apply(s, x)
                    })
                }
                function y(e) {
                    for (var t, n, i, r = e.length, o = S.relative[e[0].type], s = o || S.relative[" "], a = o ? 1 : 0, l = f(function (e) {
                        return e === t
                    }, s, !0), c = f(function (e) {
                        return ee(t, e) > -1
                    }, s, !0), u = [function (e, n, i) {
                            var r = !o && (i || n !== $) || ((t = n).nodeType ? l(e, n, i) : c(e, n, i));
                            return t = null, r
                        }]; r > a; a++)
                        if (n = S.relative[e[a].type])
                            u = [f(h(u), n)];
                        else {
                            if (n = S.filter[e[a].type].apply(null, e[a].matches), n[O]) {
                                for (i = ++a; r > i && !S.relative[e[i].type]; i++)
                                    ;
                                return v(a > 1 && h(u), a > 1 && p(e.slice(0, a - 1).concat({value: " " === e[a - 2].type ? "*" : ""})).replace(le, "$1"), n, i > a && y(e.slice(a, i)), r > i && y(e = e.slice(i)), r > i && p(e))
                            }
                            u.push(n)
                        }
                    return h(u)
                }
                function x(e, n) {
                    var r = n.length > 0, o = e.length > 0, s = function (i, s, a, l, c) {
                        var u, d, p, f = 0, h = "0", g = i && [], v = [], y = $, x = i || o && S.find.TAG("*", c), b = I += null == y ? 1 : Math.random() || .1, w = x.length;
                        for (c && ($ = s !== D && s); h !== w && null != (u = x[h]); h++) {
                            if (o && u) {
                                for (d = 0; p = e[d++]; )
                                    if (p(u, s, a)) {
                                        l.push(u);
                                        break
                                    }
                                c && (I = b)
                            }
                            r && ((u = !p && u) && f--, i && g.push(u))
                        }
                        if (f += h, r && h !== f) {
                            for (d = 0; p = n[d++]; )
                                p(g, v, s, a);
                            if (i) {
                                if (f > 0)
                                    for (; h--; )
                                        g[h] || v[h] || (v[h] = J.call(l));
                                v = m(v)
                            }
                            K.apply(l, v), c && !i && v.length > 0 && f + n.length > 1 && t.uniqueSort(l)
                        }
                        return c && (I = b, $ = y), g
                    };
                    return r ? i(s) : s
                }
                var b, w, S, T, E, C, k, N, $, A, W, _, D, j, L, M, H, q, F, O = "sizzle" + 1 * new Date, P = e.document, I = 0, z = 0, B = n(), R = n(), X = n(), V = function (e, t) {
                    return e === t && (W = !0), 0
                }, Q = 1 << 31, U = {}.hasOwnProperty, Y = [], J = Y.pop, G = Y.push, K = Y.push, Z = Y.slice, ee = function (e, t) {
                    for (var n = 0, i = e.length; i > n; n++)
                        if (e[n] === t)
                            return n;
                    return-1
                }, te = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", ne = "[\\x20\\t\\r\\n\\f]", ie = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", re = ie.replace("w", "w#"), oe = "\\[" + ne + "*(" + ie + ")(?:" + ne + "*([*^$|!~]?=)" + ne + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + re + "))|)" + ne + "*\\]", se = ":(" + ie + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + oe + ")*)|.*)\\)|)", ae = new RegExp(ne + "+", "g"), le = new RegExp("^" + ne + "+|((?:^|[^\\\\])(?:\\\\.)*)" + ne + "+$", "g"), ce = new RegExp("^" + ne + "*," + ne + "*"), ue = new RegExp("^" + ne + "*([>+~]|" + ne + ")" + ne + "*"), de = new RegExp("=" + ne + "*([^\\]'\"]*?)" + ne + "*\\]", "g"), pe = new RegExp(se), fe = new RegExp("^" + re + "$"), he = {ID: new RegExp("^#(" + ie + ")"), CLASS: new RegExp("^\\.(" + ie + ")"), TAG: new RegExp("^(" + ie.replace("w", "w*") + ")"), ATTR: new RegExp("^" + oe), PSEUDO: new RegExp("^" + se), CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + ne + "*(even|odd|(([+-]|)(\\d*)n|)" + ne + "*(?:([+-]|)" + ne + "*(\\d+)|))" + ne + "*\\)|)", "i"), bool: new RegExp("^(?:" + te + ")$", "i"), needsContext: new RegExp("^" + ne + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + ne + "*((?:-\\d)?\\d*)" + ne + "*\\)|)(?=[^-]|$)", "i")}, ge = /^(?:input|select|textarea|button)$/i, me = /^h\d$/i, ve = /^[^{]+\{\s*\[native \w/, ye = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, xe = /[+~]/, be = /'|\\/g, we = new RegExp("\\\\([\\da-f]{1,6}" + ne + "?|(" + ne + ")|.)", "ig"), Se = function (e, t, n) {
                    var i = "0x" + t - 65536;
                    return i !== i || n ? t : 0 > i ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
                }, Te = function () {
                    _()
                };
                try {
                    K.apply(Y = Z.call(P.childNodes), P.childNodes), Y[P.childNodes.length].nodeType
                } catch (Ee) {
                    K = {apply: Y.length ? function (e, t) {
                            G.apply(e, Z.call(t))
                        } : function (e, t) {
                            for (var n = e.length, i = 0; e[n++] = t[i++]; )
                                ;
                            e.length = n - 1
                        }}
                }
                w = t.support = {}, E = t.isXML = function (e) {
                    var t = e && (e.ownerDocument || e).documentElement;
                    return t ? "HTML" !== t.nodeName : !1
                }, _ = t.setDocument = function (e) {
                    var t, n, i = e ? e.ownerDocument || e : P;
                    return i !== D && 9 === i.nodeType && i.documentElement ? (D = i, j = i.documentElement, n = i.defaultView, n && n !== n.top && (n.addEventListener ? n.addEventListener("unload", Te, !1) : n.attachEvent && n.attachEvent("onunload", Te)), L = !E(i), w.attributes = r(function (e) {
                        return e.className = "i", !e.getAttribute("className")
                    }), w.getElementsByTagName = r(function (e) {
                        return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
                    }), w.getElementsByClassName = ve.test(i.getElementsByClassName), w.getById = r(function (e) {
                        return j.appendChild(e).id = O, !i.getElementsByName || !i.getElementsByName(O).length
                    }), w.getById ? (S.find.ID = function (e, t) {
                        if ("undefined" != typeof t.getElementById && L) {
                            var n = t.getElementById(e);
                            return n && n.parentNode ? [n] : []
                        }
                    }, S.filter.ID = function (e) {
                        var t = e.replace(we, Se);
                        return function (e) {
                            return e.getAttribute("id") === t
                        }
                    }) : (delete S.find.ID, S.filter.ID = function (e) {
                        var t = e.replace(we, Se);
                        return function (e) {
                            var n = "undefined" != typeof e.getAttributeNode && e.getAttributeNode("id");
                            return n && n.value === t
                        }
                    }), S.find.TAG = w.getElementsByTagName ? function (e, t) {
                        return"undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName(e) : w.qsa ? t.querySelectorAll(e) : void 0
                    } : function (e, t) {
                        var n, i = [], r = 0, o = t.getElementsByTagName(e);
                        if ("*" === e) {
                            for (; n = o[r++]; )
                                1 === n.nodeType && i.push(n);
                            return i
                        }
                        return o
                    }, S.find.CLASS = w.getElementsByClassName && function (e, t) {
                        return L ? t.getElementsByClassName(e) : void 0
                    }, H = [], M = [], (w.qsa = ve.test(i.querySelectorAll)) && (r(function (e) {
                        j.appendChild(e).innerHTML = "<a id='" + O + "'></a><select id='" + O + "-\f]' msallowcapture=''><option selected=''></option></select>", e.querySelectorAll("[msallowcapture^='']").length && M.push("[*^$]=" + ne + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || M.push("\\[" + ne + "*(?:value|" + te + ")"), e.querySelectorAll("[id~=" + O + "-]").length || M.push("~="), e.querySelectorAll(":checked").length || M.push(":checked"), e.querySelectorAll("a#" + O + "+*").length || M.push(".#.+[+~]")
                    }), r(function (e) {
                        var t = i.createElement("input");
                        t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && M.push("name" + ne + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || M.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), M.push(",.*:")
                    })), (w.matchesSelector = ve.test(q = j.matches || j.webkitMatchesSelector || j.mozMatchesSelector || j.oMatchesSelector || j.msMatchesSelector)) && r(function (e) {
                        w.disconnectedMatch = q.call(e, "div"), q.call(e, "[s!='']:x"), H.push("!=", se)
                    }), M = M.length && new RegExp(M.join("|")), H = H.length && new RegExp(H.join("|")), t = ve.test(j.compareDocumentPosition), F = t || ve.test(j.contains) ? function (e, t) {
                        var n = 9 === e.nodeType ? e.documentElement : e, i = t && t.parentNode;
                        return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
                    } : function (e, t) {
                        if (t)
                            for (; t = t.parentNode; )
                                if (t === e)
                                    return!0;
                        return!1
                    }, V = t ? function (e, t) {
                        if (e === t)
                            return W = !0, 0;
                        var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                        return n ? n : (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & n || !w.sortDetached && t.compareDocumentPosition(e) === n ? e === i || e.ownerDocument === P && F(P, e) ? -1 : t === i || t.ownerDocument === P && F(P, t) ? 1 : A ? ee(A, e) - ee(A, t) : 0 : 4 & n ? -1 : 1)
                    } : function (e, t) {
                        if (e === t)
                            return W = !0, 0;
                        var n, r = 0, o = e.parentNode, a = t.parentNode, l = [e], c = [t];
                        if (!o || !a)
                            return e === i ? -1 : t === i ? 1 : o ? -1 : a ? 1 : A ? ee(A, e) - ee(A, t) : 0;
                        if (o === a)
                            return s(e, t);
                        for (n = e; n = n.parentNode; )
                            l.unshift(n);
                        for (n = t; n = n.parentNode; )
                            c.unshift(n);
                        for (; l[r] === c[r]; )
                            r++;
                        return r ? s(l[r], c[r]) : l[r] === P ? -1 : c[r] === P ? 1 : 0
                    }, i) : D
                }, t.matches = function (e, n) {
                    return t(e, null, null, n)
                }, t.matchesSelector = function (e, n) {
                    if ((e.ownerDocument || e) !== D && _(e), n = n.replace(de, "='$1']"), w.matchesSelector && L && (!H || !H.test(n)) && (!M || !M.test(n)))
                        try {
                            var i = q.call(e, n);
                            if (i || w.disconnectedMatch || e.document && 11 !== e.document.nodeType)
                                return i
                        } catch (r) {
                        }
                    return t(n, D, null, [e]).length > 0
                }, t.contains = function (e, t) {
                    return(e.ownerDocument || e) !== D && _(e), F(e, t)
                }, t.attr = function (e, t) {
                    (e.ownerDocument || e) !== D && _(e);
                    var n = S.attrHandle[t.toLowerCase()], i = n && U.call(S.attrHandle, t.toLowerCase()) ? n(e, t, !L) : void 0;
                    return void 0 !== i ? i : w.attributes || !L ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
                }, t.error = function (e) {
                    throw new Error("Syntax error, unrecognized expression: " + e)
                }, t.uniqueSort = function (e) {
                    var t, n = [], i = 0, r = 0;
                    if (W = !w.detectDuplicates, A = !w.sortStable && e.slice(0), e.sort(V), W) {
                        for (; t = e[r++]; )
                            t === e[r] && (i = n.push(r));
                        for (; i--; )
                            e.splice(n[i], 1)
                    }
                    return A = null, e
                }, T = t.getText = function (e) {
                    var t, n = "", i = 0, r = e.nodeType;
                    if (r) {
                        if (1 === r || 9 === r || 11 === r) {
                            if ("string" == typeof e.textContent)
                                return e.textContent;
                            for (e = e.firstChild; e; e = e.nextSibling)
                                n += T(e)
                        } else if (3 === r || 4 === r)
                            return e.nodeValue
                    } else
                        for (; t = e[i++]; )
                            n += T(t);
                    return n
                }, S = t.selectors = {cacheLength: 50, createPseudo: i, match: he, attrHandle: {}, find: {}, relative: {">": {dir: "parentNode", first: !0}, " ": {dir: "parentNode"}, "+": {dir: "previousSibling", first: !0}, "~": {dir: "previousSibling"}}, preFilter: {ATTR: function (e) {
                            return e[1] = e[1].replace(we, Se), e[3] = (e[3] || e[4] || e[5] || "").replace(we, Se), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                        }, CHILD: function (e) {
                            return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || t.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && t.error(e[0]), e
                        }, PSEUDO: function (e) {
                            var t, n = !e[6] && e[2];
                            return he.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && pe.test(n) && (t = C(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                        }}, filter: {TAG: function (e) {
                            var t = e.replace(we, Se).toLowerCase();
                            return"*" === e ? function () {
                                return!0
                            } : function (e) {
                                return e.nodeName && e.nodeName.toLowerCase() === t
                            }
                        }, CLASS: function (e) {
                            var t = B[e + " "];
                            return t || (t = new RegExp("(^|" + ne + ")" + e + "(" + ne + "|$)")) && B(e, function (e) {
                                return t.test("string" == typeof e.className && e.className || "undefined" != typeof e.getAttribute && e.getAttribute("class") || "")
                            })
                        }, ATTR: function (e, n, i) {
                            return function (r) {
                                var o = t.attr(r, e);
                                return null == o ? "!=" === n : n ? (o += "", "=" === n ? o === i : "!=" === n ? o !== i : "^=" === n ? i && 0 === o.indexOf(i) : "*=" === n ? i && o.indexOf(i) > -1 : "$=" === n ? i && o.slice(-i.length) === i : "~=" === n ? (" " + o.replace(ae, " ") + " ").indexOf(i) > -1 : "|=" === n ? o === i || o.slice(0, i.length + 1) === i + "-" : !1) : !0
                            }
                        }, CHILD: function (e, t, n, i, r) {
                            var o = "nth" !== e.slice(0, 3), s = "last" !== e.slice(-4), a = "of-type" === t;
                            return 1 === i && 0 === r ? function (e) {
                                return!!e.parentNode
                            } : function (t, n, l) {
                                var c, u, d, p, f, h, g = o !== s ? "nextSibling" : "previousSibling", m = t.parentNode, v = a && t.nodeName.toLowerCase(), y = !l && !a;
                                if (m) {
                                    if (o) {
                                        for (; g; ) {
                                            for (d = t; d = d[g]; )
                                                if (a ? d.nodeName.toLowerCase() === v : 1 === d.nodeType)
                                                    return!1;
                                            h = g = "only" === e && !h && "nextSibling"
                                        }
                                        return!0
                                    }
                                    if (h = [s ? m.firstChild : m.lastChild], s && y) {
                                        for (u = m[O] || (m[O] = {}), c = u[e] || [], f = c[0] === I && c[1], p = c[0] === I && c[2], d = f && m.childNodes[f]; d = ++f && d && d[g] || (p = f = 0) || h.pop(); )
                                            if (1 === d.nodeType && ++p && d === t) {
                                                u[e] = [I, f, p];
                                                break
                                            }
                                    } else if (y && (c = (t[O] || (t[O] = {}))[e]) && c[0] === I)
                                        p = c[1];
                                    else
                                        for (; (d = ++f && d && d[g] || (p = f = 0) || h.pop()) && ((a?d.nodeName.toLowerCase() !== v:1 !== d.nodeType) || !++p || (y && ((d[O] || (d[O] = {}))[e] = [I, p]), d !== t)); )
                                            ;
                                    return p -= r, p === i || p % i === 0 && p / i >= 0
                                }
                            }
                        }, PSEUDO: function (e, n) {
                            var r, o = S.pseudos[e] || S.setFilters[e.toLowerCase()] || t.error("unsupported pseudo: " + e);
                            return o[O] ? o(n) : o.length > 1 ? (r = [e, e, "", n], S.setFilters.hasOwnProperty(e.toLowerCase()) ? i(function (e, t) {
                                for (var i, r = o(e, n), s = r.length; s--; )
                                    i = ee(e, r[s]), e[i] = !(t[i] = r[s])
                            }) : function (e) {
                                return o(e, 0, r)
                            }) : o
                        }}, pseudos: {not: i(function (e) {
                            var t = [], n = [], r = k(e.replace(le, "$1"));
                            return r[O] ? i(function (e, t, n, i) {
                                for (var o, s = r(e, null, i, []), a = e.length; a--; )
                                    (o = s[a]) && (e[a] = !(t[a] = o))
                            }) : function (e, i, o) {
                                return t[0] = e, r(t, null, o, n), t[0] = null, !n.pop()
                            }
                        }), has: i(function (e) {
                            return function (n) {
                                return t(e, n).length > 0
                            }
                        }), contains: i(function (e) {
                            return e = e.replace(we, Se), function (t) {
                                return(t.textContent || t.innerText || T(t)).indexOf(e) > -1
                            }
                        }), lang: i(function (e) {
                            return fe.test(e || "") || t.error("unsupported lang: " + e), e = e.replace(we, Se).toLowerCase(), function (t) {
                                var n;
                                do
                                    if (n = L ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))
                                        return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-");
                                while ((t = t.parentNode) && 1 === t.nodeType);
                                return!1
                            }
                        }), target: function (t) {
                            var n = e.location && e.location.hash;
                            return n && n.slice(1) === t.id
                        }, root: function (e) {
                            return e === j
                        }, focus: function (e) {
                            return e === D.activeElement && (!D.hasFocus || D.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                        }, enabled: function (e) {
                            return e.disabled === !1
                        }, disabled: function (e) {
                            return e.disabled === !0
                        }, checked: function (e) {
                            var t = e.nodeName.toLowerCase();
                            return"input" === t && !!e.checked || "option" === t && !!e.selected
                        }, selected: function (e) {
                            return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                        }, empty: function (e) {
                            for (e = e.firstChild; e; e = e.nextSibling)
                                if (e.nodeType < 6)
                                    return!1;
                            return!0
                        }, parent: function (e) {
                            return!S.pseudos.empty(e)
                        }, header: function (e) {
                            return me.test(e.nodeName)
                        }, input: function (e) {
                            return ge.test(e.nodeName)
                        }, button: function (e) {
                            var t = e.nodeName.toLowerCase();
                            return"input" === t && "button" === e.type || "button" === t
                        }, text: function (e) {
                            var t;
                            return"input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                        }, first: c(function () {
                            return[0]
                        }), last: c(function (e, t) {
                            return[t - 1]
                        }), eq: c(function (e, t, n) {
                            return[0 > n ? n + t : n]
                        }), even: c(function (e, t) {
                            for (var n = 0; t > n; n += 2)
                                e.push(n);
                            return e
                        }), odd: c(function (e, t) {
                            for (var n = 1; t > n; n += 2)
                                e.push(n);
                            return e
                        }), lt: c(function (e, t, n) {
                            for (var i = 0 > n ? n + t : n; --i >= 0; )
                                e.push(i);
                            return e
                        }), gt: c(function (e, t, n) {
                            for (var i = 0 > n ? n + t : n; ++i < t; )
                                e.push(i);
                            return e
                        })}}, S.pseudos.nth = S.pseudos.eq;
                for (b in{radio:!0, checkbox:!0, file:!0, password:!0, image:!0})
                    S.pseudos[b] = a(b);
                for (b in{submit:!0, reset:!0})
                    S.pseudos[b] = l(b);
                return d.prototype = S.filters = S.pseudos, S.setFilters = new d, C = t.tokenize = function (e, n) {
                    var i, r, o, s, a, l, c, u = R[e + " "];
                    if (u)
                        return n ? 0 : u.slice(0);
                    for (a = e, l = [], c = S.preFilter; a; ) {
                        (!i || (r = ce.exec(a))) && (r && (a = a.slice(r[0].length) || a), l.push(o = [])), i = !1, (r = ue.exec(a)) && (i = r.shift(), o.push({value: i, type: r[0].replace(le, " ")}), a = a.slice(i.length));
                        for (s in S.filter)
                            !(r = he[s].exec(a)) || c[s] && !(r = c[s](r)) || (i = r.shift(), o.push({value: i, type: s, matches: r}), a = a.slice(i.length));
                        if (!i)
                            break
                    }
                    return n ? a.length : a ? t.error(e) : R(e, l).slice(0)
                }, k = t.compile = function (e, t) {
                    var n, i = [], r = [], o = X[e + " "];
                    if (!o) {
                        for (t || (t = C(e)), n = t.length; n--; )
                            o = y(t[n]), o[O] ? i.push(o) : r.push(o);
                        o = X(e, x(r, i)), o.selector = e
                    }
                    return o
                }, N = t.select = function (e, t, n, i) {
                    var r, o, s, a, l, c = "function" == typeof e && e, d = !i && C(e = c.selector || e);
                    if (n = n || [], 1 === d.length) {
                        if (o = d[0] = d[0].slice(0), o.length > 2 && "ID" === (s = o[0]).type && w.getById && 9 === t.nodeType && L && S.relative[o[1].type]) {
                            if (t = (S.find.ID(s.matches[0].replace(we, Se), t) || [])[0], !t)
                                return n;
                            c && (t = t.parentNode), e = e.slice(o.shift().value.length)
                        }
                        for (r = he.needsContext.test(e)?0:o.length; r-- && (s = o[r], !S.relative[a = s.type]); )
                            if ((l = S.find[a]) && (i = l(s.matches[0].replace(we, Se), xe.test(o[0].type) && u(t.parentNode) || t))) {
                                if (o.splice(r, 1), e = i.length && p(o), !e)
                                    return K.apply(n, i), n;
                                break
                            }
                    }
                    return(c || k(e, d))(i, t, !L, n, xe.test(e) && u(t.parentNode) || t), n
                }, w.sortStable = O.split("").sort(V).join("") === O, w.detectDuplicates = !!W, _(), w.sortDetached = r(function (e) {
                    return 1 & e.compareDocumentPosition(D.createElement("div"))
                }), r(function (e) {
                    return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
                }) || o("type|href|height|width", function (e, t, n) {
                    return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
                }), w.attributes && r(function (e) {
                    return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
                }) || o("value", function (e, t, n) {
                    return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
                }), r(function (e) {
                    return null == e.getAttribute("disabled")
                }) || o(te, function (e, t, n) {
                    var i;
                    return n ? void 0 : e[t] === !0 ? t.toLowerCase() : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
                }), t
            }(e);
    re.find = ce, re.expr = ce.selectors, re.expr[":"] = re.expr.pseudos, re.unique = ce.uniqueSort, re.text = ce.getText, re.isXMLDoc = ce.isXML, re.contains = ce.contains;
    var ue = re.expr.match.needsContext, de = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, pe = /^.[^:#\[\.,]*$/;
    re.filter = function (e, t, n) {
        var i = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === i.nodeType ? re.find.matchesSelector(i, e) ? [i] : [] : re.find.matches(e, re.grep(t, function (e) {
            return 1 === e.nodeType
        }))
    }, re.fn.extend({find: function (e) {
            var t, n = [], i = this, r = i.length;
            if ("string" != typeof e)
                return this.pushStack(re(e).filter(function () {
                    for (t = 0; r > t; t++)
                        if (re.contains(i[t], this))
                            return!0
                }));
            for (t = 0; r > t; t++)
                re.find(e, i[t], n);
            return n = this.pushStack(r > 1 ? re.unique(n) : n), n.selector = this.selector ? this.selector + " " + e : e, n
        }, filter: function (e) {
            return this.pushStack(i(this, e || [], !1))
        }, not: function (e) {
            return this.pushStack(i(this, e || [], !0))
        }, is: function (e) {
            return!!i(this, "string" == typeof e && ue.test(e) ? re(e) : e || [], !1).length
        }});
    var fe, he = e.document, ge = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, me = re.fn.init = function (e, t) {
        var n, i;
        if (!e)
            return this;
        if ("string" == typeof e) {
            if (n = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : ge.exec(e), !n || !n[1] && t)
                return!t || t.jquery ? (t || fe).find(e) : this.constructor(t).find(e);
            if (n[1]) {
                if (t = t instanceof re ? t[0] : t, re.merge(this, re.parseHTML(n[1], t && t.nodeType ? t.ownerDocument || t : he, !0)), de.test(n[1]) && re.isPlainObject(t))
                    for (n in t)
                        re.isFunction(this[n]) ? this[n](t[n]) : this.attr(n, t[n]);
                return this
            }
            if (i = he.getElementById(n[2]), i && i.parentNode) {
                if (i.id !== n[2])
                    return fe.find(e);
                this.length = 1, this[0] = i
            }
            return this.context = he, this.selector = e, this
        }
        return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : re.isFunction(e) ? "undefined" != typeof fe.ready ? fe.ready(e) : e(re) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), re.makeArray(e, this))
    };
    me.prototype = re.fn, fe = re(he);
    var ve = /^(?:parents|prev(?:Until|All))/, ye = {children: !0, contents: !0, next: !0, prev: !0};
    re.extend({dir: function (e, t, n) {
            for (var i = [], r = e[t]; r && 9 !== r.nodeType && (void 0 === n || 1 !== r.nodeType || !re(r).is(n)); )
                1 === r.nodeType && i.push(r), r = r[t];
            return i
        }, sibling: function (e, t) {
            for (var n = []; e; e = e.nextSibling)
                1 === e.nodeType && e !== t && n.push(e);
            return n
        }}), re.fn.extend({has: function (e) {
            var t, n = re(e, this), i = n.length;
            return this.filter(function () {
                for (t = 0; i > t; t++)
                    if (re.contains(this, n[t]))
                        return!0
            })
        }, closest: function (e, t) {
            for (var n, i = 0, r = this.length, o = [], s = ue.test(e) || "string" != typeof e ? re(e, t || this.context) : 0; r > i; i++)
                for (n = this[i]; n && n !== t; n = n.parentNode)
                    if (n.nodeType < 11 && (s ? s.index(n) > -1 : 1 === n.nodeType && re.find.matchesSelector(n, e))) {
                        o.push(n);
                        break
                    }
            return this.pushStack(o.length > 1 ? re.unique(o) : o)
        }, index: function (e) {
            return e ? "string" == typeof e ? re.inArray(this[0], re(e)) : re.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (e, t) {
            return this.pushStack(re.unique(re.merge(this.get(), re(e, t))))
        }, addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }}), re.each({parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        }, parents: function (e) {
            return re.dir(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return re.dir(e, "parentNode", n)
        }, next: function (e) {
            return r(e, "nextSibling")
        }, prev: function (e) {
            return r(e, "previousSibling")
        }, nextAll: function (e) {
            return re.dir(e, "nextSibling")
        }, prevAll: function (e) {
            return re.dir(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return re.dir(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return re.dir(e, "previousSibling", n)
        }, siblings: function (e) {
            return re.sibling((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return re.sibling(e.firstChild)
        }, contents: function (e) {
            return re.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : re.merge([], e.childNodes)
        }}, function (e, t) {
        re.fn[e] = function (n, i) {
            var r = re.map(this, t, n);
            return"Until" !== e.slice(-5) && (i = n), i && "string" == typeof i && (r = re.filter(i, r)), this.length > 1 && (ye[e] || (r = re.unique(r)), ve.test(e) && (r = r.reverse())), this.pushStack(r)
        }
    });
    var xe = /\S+/g, be = {};
    re.Callbacks = function (e) {
        e = "string" == typeof e ? be[e] || o(e) : re.extend({}, e);
        var t, n, i, r, s, a, l = [], c = !e.once && [], u = function (o) {
            for (n = e.memory && o, i = !0, s = a || 0, a = 0, r = l.length, t = !0; l && r > s; s++)
                if (l[s].apply(o[0], o[1]) === !1 && e.stopOnFalse) {
                    n = !1;
                    break
                }
            t = !1, l && (c ? c.length && u(c.shift()) : n ? l = [] : d.disable())
        }, d = {add: function () {
                if (l) {
                    var i = l.length;
                    !function o(t) {
                        re.each(t, function (t, n) {
                            var i = re.type(n);
                            "function" === i ? e.unique && d.has(n) || l.push(n) : n && n.length && "string" !== i && o(n)
                        })
                    }(arguments), t ? r = l.length : n && (a = i, u(n))
                }
                return this
            }, remove: function () {
                return l && re.each(arguments, function (e, n) {
                    for (var i; (i = re.inArray(n, l, i)) > - 1; )
                        l.splice(i, 1), t && (r >= i && r--, s >= i && s--)
                }), this
            }, has: function (e) {
                return e ? re.inArray(e, l) > -1 : !(!l || !l.length)
            }, empty: function () {
                return l = [], r = 0, this
            }, disable: function () {
                return l = c = n = void 0, this
            }, disabled: function () {
                return!l
            }, lock: function () {
                return c = void 0, n || d.disable(), this
            }, locked: function () {
                return!c
            }, fireWith: function (e, n) {
                return!l || i && !c || (n = n || [], n = [e, n.slice ? n.slice() : n], t ? c.push(n) : u(n)), this
            }, fire: function () {
                return d.fireWith(this, arguments), this
            }, fired: function () {
                return!!i
            }};
        return d
    }, re.extend({Deferred: function (e) {
            var t = [["resolve", "done", re.Callbacks("once memory"), "resolved"], ["reject", "fail", re.Callbacks("once memory"), "rejected"], ["notify", "progress", re.Callbacks("memory")]], n = "pending", i = {state: function () {
                    return n
                }, always: function () {
                    return r.done(arguments).fail(arguments), this
                }, then: function () {
                    var e = arguments;
                    return re.Deferred(function (n) {
                        re.each(t, function (t, o) {
                            var s = re.isFunction(e[t]) && e[t];
                            r[o[1]](function () {
                                var e = s && s.apply(this, arguments);
                                e && re.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[o[0] + "With"](this === i ? n.promise() : this, s ? [e] : arguments)
                            })
                        }), e = null
                    }).promise()
                }, promise: function (e) {
                    return null != e ? re.extend(e, i) : i
                }}, r = {};
            return i.pipe = i.then, re.each(t, function (e, o) {
                var s = o[2], a = o[3];
                i[o[1]] = s.add, a && s.add(function () {
                    n = a
                }, t[1 ^ e][2].disable, t[2][2].lock), r[o[0]] = function () {
                    return r[o[0] + "With"](this === r ? i : this, arguments), this
                }, r[o[0] + "With"] = s.fireWith
            }), i.promise(r), e && e.call(r, r), r
        }, when: function (e) {
            var t, n, i, r = 0, o = Y.call(arguments), s = o.length, a = 1 !== s || e && re.isFunction(e.promise) ? s : 0, l = 1 === a ? e : re.Deferred(), c = function (e, n, i) {
                return function (r) {
                    n[e] = this, i[e] = arguments.length > 1 ? Y.call(arguments) : r, i === t ? l.notifyWith(n, i) : --a || l.resolveWith(n, i)
                }
            };
            if (s > 1)
                for (t = new Array(s), n = new Array(s), i = new Array(s); s > r; r++)
                    o[r] && re.isFunction(o[r].promise) ? o[r].promise().done(c(r, i, o)).fail(l.reject).progress(c(r, n, t)) : --a;
            return a || l.resolveWith(i, o), l.promise()
        }});
    var we;
    re.fn.ready = function (e) {
        return re.ready.promise().done(e), this
    }, re.extend({isReady: !1, readyWait: 1, holdReady: function (e) {
            e ? re.readyWait++ : re.ready(!0)
        }, ready: function (e) {
            if (e === !0 ? !--re.readyWait : !re.isReady) {
                if (!he.body)
                    return setTimeout(re.ready);
                re.isReady = !0, e !== !0 && --re.readyWait > 0 || (we.resolveWith(he, [re]), re.fn.triggerHandler && (re(he).triggerHandler("ready"), re(he).off("ready")))
            }
        }}), re.ready.promise = function (t) {
        if (!we)
            if (we = re.Deferred(), "complete" === he.readyState)
                setTimeout(re.ready);
            else if (he.addEventListener)
                he.addEventListener("DOMContentLoaded", a, !1), e.addEventListener("load", a, !1);
            else {
                he.attachEvent("onreadystatechange", a), e.attachEvent("onload", a);
                var n = !1;
                try {
                    n = null == e.frameElement && he.documentElement
                } catch (i) {
                }
                n && n.doScroll && !function r() {
                    if (!re.isReady) {
                        try {
                            n.doScroll("left")
                        } catch (e) {
                            return setTimeout(r, 50)
                        }
                        s(), re.ready()
                    }
                }()
            }
        return we.promise(t)
    };
    var Se, Te = "undefined";
    for (Se in re(ne))
        break;
    ne.ownLast = "0" !== Se, ne.inlineBlockNeedsLayout = !1, re(function () {
        var e, t, n, i;
        n = he.getElementsByTagName("body")[0], n && n.style && (t = he.createElement("div"), i = he.createElement("div"), i.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(i).appendChild(t), typeof t.style.zoom !== Te && (t.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", ne.inlineBlockNeedsLayout = e = 3 === t.offsetWidth, e && (n.style.zoom = 1)), n.removeChild(i))
    }), function () {
        var e = he.createElement("div");
        if (null == ne.deleteExpando) {
            ne.deleteExpando = !0;
            try {
                delete e.test
            } catch (t) {
                ne.deleteExpando = !1
            }
        }
        e = null
    }(), re.acceptData = function (e) {
        var t = re.noData[(e.nodeName + " ").toLowerCase()], n = +e.nodeType || 1;
        return 1 !== n && 9 !== n ? !1 : !t || t !== !0 && e.getAttribute("classid") === t
    };
    var Ee = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, Ce = /([A-Z])/g;
    re.extend({cache: {}, noData: {"applet ": !0, "embed ": !0, "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"}, hasData: function (e) {
            return e = e.nodeType ? re.cache[e[re.expando]] : e[re.expando], !!e && !c(e)
        }, data: function (e, t, n) {
            return u(e, t, n)
        }, removeData: function (e, t) {
            return d(e, t)
        }, _data: function (e, t, n) {
            return u(e, t, n, !0)
        }, _removeData: function (e, t) {
            return d(e, t, !0)
        }}), re.fn.extend({data: function (e, t) {
            var n, i, r, o = this[0], s = o && o.attributes;
            if (void 0 === e) {
                if (this.length && (r = re.data(o), 1 === o.nodeType && !re._data(o, "parsedAttrs"))) {
                    for (n = s.length; n--; )
                        s[n] && (i = s[n].name, 0 === i.indexOf("data-") && (i = re.camelCase(i.slice(5)), l(o, i, r[i])));
                    re._data(o, "parsedAttrs", !0)
                }
                return r
            }
            return"object" == typeof e ? this.each(function () {
                re.data(this, e)
            }) : arguments.length > 1 ? this.each(function () {
                re.data(this, e, t)
            }) : o ? l(o, e, re.data(o, e)) : void 0
        }, removeData: function (e) {
            return this.each(function () {
                re.removeData(this, e)
            })
        }}), re.extend({queue: function (e, t, n) {
            var i;
            return e ? (t = (t || "fx") + "queue", i = re._data(e, t), n && (!i || re.isArray(n) ? i = re._data(e, t, re.makeArray(n)) : i.push(n)), i || []) : void 0
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = re.queue(e, t), i = n.length, r = n.shift(), o = re._queueHooks(e, t), s = function () {
                re.dequeue(e, t)
            };
            "inprogress" === r && (r = n.shift(), i--), r && ("fx" === t && n.unshift("inprogress"), delete o.stop, r.call(e, s, o)), !i && o && o.empty.fire()
        }, _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return re._data(e, n) || re._data(e, n, {empty: re.Callbacks("once memory").add(function () {
                    re._removeData(e, t + "queue"), re._removeData(e, n)
                })})
        }}), re.fn.extend({queue: function (e, t) {
            var n = 2;
            return"string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? re.queue(this[0], e) : void 0 === t ? this : this.each(function () {
                var n = re.queue(this, e, t);
                re._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && re.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                re.dequeue(this, e)
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, t) {
            var n, i = 1, r = re.Deferred(), o = this, s = this.length, a = function () {
                --i || r.resolveWith(o, [o])
            };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; s--; )
                n = re._data(o[s], e + "queueHooks"), n && n.empty && (i++, n.empty.add(a));
            return a(), r.promise(t)
        }});
    var ke = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, Ne = ["Top", "Right", "Bottom", "Left"], $e = function (e, t) {
        return e = t || e, "none" === re.css(e, "display") || !re.contains(e.ownerDocument, e)
    }, Ae = re.access = function (e, t, n, i, r, o, s) {
        var a = 0, l = e.length, c = null == n;
        if ("object" === re.type(n)) {
            r = !0;
            for (a in n)
                re.access(e, t, a, n[a], !0, o, s)
        } else if (void 0 !== i && (r = !0, re.isFunction(i) || (s = !0), c && (s ? (t.call(e, i), t = null) : (c = t, t = function (e, t, n) {
            return c.call(re(e), n)
        })), t))
            for (; l > a; a++)
                t(e[a], n, s ? i : i.call(e[a], a, t(e[a], n)));
        return r ? e : c ? t.call(e) : l ? t(e[0], n) : o
    }, We = /^(?:checkbox|radio)$/i;
    !function () {
        var e = he.createElement("input"), t = he.createElement("div"), n = he.createDocumentFragment();
        if (t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", ne.leadingWhitespace = 3 === t.firstChild.nodeType, ne.tbody = !t.getElementsByTagName("tbody").length, ne.htmlSerialize = !!t.getElementsByTagName("link").length, ne.html5Clone = "<:nav></:nav>" !== he.createElement("nav").cloneNode(!0).outerHTML, e.type = "checkbox", e.checked = !0, n.appendChild(e), ne.appendChecked = e.checked, t.innerHTML = "<textarea>x</textarea>", ne.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, n.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", ne.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, ne.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick", function () {
            ne.noCloneEvent = !1
        }), t.cloneNode(!0).click()), null == ne.deleteExpando) {
            ne.deleteExpando = !0;
            try {
                delete t.test
            } catch (i) {
                ne.deleteExpando = !1
            }
        }
    }(), function () {
        var t, n, i = he.createElement("div");
        for (t in{submit:!0, change:!0, focusin:!0})
            n = "on" + t, (ne[t + "Bubbles"] = n in e) || (i.setAttribute(n, "t"), ne[t + "Bubbles"] = i.attributes[n].expando === !1);
        i = null
    }();
    var _e = /^(?:input|select|textarea)$/i, De = /^key/, je = /^(?:mouse|pointer|contextmenu)|click/, Le = /^(?:focusinfocus|focusoutblur)$/, Me = /^([^.]*)(?:\.(.+)|)$/;
    re.event = {global: {}, add: function (e, t, n, i, r) {
            var o, s, a, l, c, u, d, p, f, h, g, m = re._data(e);
            if (m) {
                for (n.handler && (l = n, n = l.handler, r = l.selector), n.guid || (n.guid = re.guid++), (s = m.events) || (s = m.events = {}), (u = m.handle) || (u = m.handle = function (e) {
                    return typeof re === Te || e && re.event.triggered === e.type ? void 0 : re.event.dispatch.apply(u.elem, arguments)
                }, u.elem = e), t = (t || "").match(xe) || [""], a = t.length; a--; )
                    o = Me.exec(t[a]) || [], f = g = o[1], h = (o[2] || "").split(".").sort(), f && (c = re.event.special[f] || {}, f = (r ? c.delegateType : c.bindType) || f, c = re.event.special[f] || {}, d = re.extend({type: f, origType: g, data: i, handler: n, guid: n.guid, selector: r, needsContext: r && re.expr.match.needsContext.test(r), namespace: h.join(".")}, l), (p = s[f]) || (p = s[f] = [], p.delegateCount = 0, c.setup && c.setup.call(e, i, h, u) !== !1 || (e.addEventListener ? e.addEventListener(f, u, !1) : e.attachEvent && e.attachEvent("on" + f, u))), c.add && (c.add.call(e, d), d.handler.guid || (d.handler.guid = n.guid)), r ? p.splice(p.delegateCount++, 0, d) : p.push(d), re.event.global[f] = !0);
                e = null
            }
        }, remove: function (e, t, n, i, r) {
            var o, s, a, l, c, u, d, p, f, h, g, m = re.hasData(e) && re._data(e);
            if (m && (u = m.events)) {
                for (t = (t || "").match(xe) || [""], c = t.length; c--; )
                    if (a = Me.exec(t[c]) || [], f = g = a[1], h = (a[2] || "").split(".").sort(), f) {
                        for (d = re.event.special[f] || {}, f = (i?d.delegateType:d.bindType) || f, p = u[f] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = o = p.length; o--; )
                            s = p[o], !r && g !== s.origType || n && n.guid !== s.guid || a && !a.test(s.namespace) || i && i !== s.selector && ("**" !== i || !s.selector) || (p.splice(o, 1), s.selector && p.delegateCount--, d.remove && d.remove.call(e, s));
                        l && !p.length && (d.teardown && d.teardown.call(e, h, m.handle) !== !1 || re.removeEvent(e, f, m.handle), delete u[f])
                    } else
                        for (f in u)
                            re.event.remove(e, f + t[c], n, i, !0);
                re.isEmptyObject(u) && (delete m.handle, re._removeData(e, "events"))
            }
        }, trigger: function (t, n, i, r) {
            var o, s, a, l, c, u, d, p = [i || he], f = te.call(t, "type") ? t.type : t, h = te.call(t, "namespace") ? t.namespace.split(".") : [];
            if (a = u = i = i || he, 3 !== i.nodeType && 8 !== i.nodeType && !Le.test(f + re.event.triggered) && (f.indexOf(".") >= 0 && (h = f.split("."), f = h.shift(), h.sort()), s = f.indexOf(":") < 0 && "on" + f, t = t[re.expando] ? t : new re.Event(f, "object" == typeof t && t),
                    t.isTrigger = r ? 2 : 3, t.namespace = h.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = i), n = null == n ? [t] : re.makeArray(n, [t]), c = re.event.special[f] || {}, r || !c.trigger || c.trigger.apply(i, n) !== !1)) {
                if (!r && !c.noBubble && !re.isWindow(i)) {
                    for (l = c.delegateType || f, Le.test(l + f) || (a = a.parentNode); a; a = a.parentNode)
                        p.push(a), u = a;
                    u === (i.ownerDocument || he) && p.push(u.defaultView || u.parentWindow || e)
                }
                for (d = 0; (a = p[d++]) && !t.isPropagationStopped(); )
                    t.type = d > 1 ? l : c.bindType || f, o = (re._data(a, "events") || {})[t.type] && re._data(a, "handle"), o && o.apply(a, n), o = s && a[s], o && o.apply && re.acceptData(a) && (t.result = o.apply(a, n), t.result === !1 && t.preventDefault());
                if (t.type = f, !r && !t.isDefaultPrevented() && (!c._default || c._default.apply(p.pop(), n) === !1) && re.acceptData(i) && s && i[f] && !re.isWindow(i)) {
                    u = i[s], u && (i[s] = null), re.event.triggered = f;
                    try {
                        i[f]()
                    } catch (g) {
                    }
                    re.event.triggered = void 0, u && (i[s] = u)
                }
                return t.result
            }
        }, dispatch: function (e) {
            e = re.event.fix(e);
            var t, n, i, r, o, s = [], a = Y.call(arguments), l = (re._data(this, "events") || {})[e.type] || [], c = re.event.special[e.type] || {};
            if (a[0] = e, e.delegateTarget = this, !c.preDispatch || c.preDispatch.call(this, e) !== !1) {
                for (s = re.event.handlers.call(this, e, l), t = 0; (r = s[t++]) && !e.isPropagationStopped(); )
                    for (e.currentTarget = r.elem, o = 0; (i = r.handlers[o++]) && !e.isImmediatePropagationStopped(); )
                        (!e.namespace_re || e.namespace_re.test(i.namespace)) && (e.handleObj = i, e.data = i.data, n = ((re.event.special[i.origType] || {}).handle || i.handler).apply(r.elem, a), void 0 !== n && (e.result = n) === !1 && (e.preventDefault(), e.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, e), e.result
            }
        }, handlers: function (e, t) {
            var n, i, r, o, s = [], a = t.delegateCount, l = e.target;
            if (a && l.nodeType && (!e.button || "click" !== e.type))
                for (; l != this; l = l.parentNode || this)
                    if (1 === l.nodeType && (l.disabled !== !0 || "click" !== e.type)) {
                        for (r = [], o = 0; a > o; o++)
                            i = t[o], n = i.selector + " ", void 0 === r[n] && (r[n] = i.needsContext ? re(n, this).index(l) >= 0 : re.find(n, this, null, [l]).length), r[n] && r.push(i);
                        r.length && s.push({elem: l, handlers: r})
                    }
            return a < t.length && s.push({elem: this, handlers: t.slice(a)}), s
        }, fix: function (e) {
            if (e[re.expando])
                return e;
            var t, n, i, r = e.type, o = e, s = this.fixHooks[r];
            for (s || (this.fixHooks[r] = s = je.test(r)?this.mouseHooks:De.test(r)?this.keyHooks:{}), i = s.props?this.props.concat(s.props):this.props, e = new re.Event(o), t = i.length; t--; )
                n = i[t], e[n] = o[n];
            return e.target || (e.target = o.srcElement || he), 3 === e.target.nodeType && (e.target = e.target.parentNode), e.metaKey = !!e.metaKey, s.filter ? s.filter(e, o) : e
        }, props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "), fixHooks: {}, keyHooks: {props: "char charCode key keyCode".split(" "), filter: function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }}, mouseHooks: {props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "), filter: function (e, t) {
                var n, i, r, o = t.button, s = t.fromElement;
                return null == e.pageX && null != t.clientX && (i = e.target.ownerDocument || he, r = i.documentElement, n = i.body, e.pageX = t.clientX + (r && r.scrollLeft || n && n.scrollLeft || 0) - (r && r.clientLeft || n && n.clientLeft || 0), e.pageY = t.clientY + (r && r.scrollTop || n && n.scrollTop || 0) - (r && r.clientTop || n && n.clientTop || 0)), !e.relatedTarget && s && (e.relatedTarget = s === e.target ? t.toElement : s), e.which || void 0 === o || (e.which = 1 & o ? 1 : 2 & o ? 3 : 4 & o ? 2 : 0), e
            }}, special: {load: {noBubble: !0}, focus: {trigger: function () {
                    if (this !== h() && this.focus)
                        try {
                            return this.focus(), !1
                        } catch (e) {
                        }
                }, delegateType: "focusin"}, blur: {trigger: function () {
                    return this === h() && this.blur ? (this.blur(), !1) : void 0
                }, delegateType: "focusout"}, click: {trigger: function () {
                    return re.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                }, _default: function (e) {
                    return re.nodeName(e.target, "a")
                }}, beforeunload: {postDispatch: function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }}}, simulate: function (e, t, n, i) {
            var r = re.extend(new re.Event, n, {type: e, isSimulated: !0, originalEvent: {}});
            i ? re.event.trigger(r, null, t) : re.event.dispatch.call(t, r), r.isDefaultPrevented() && n.preventDefault()
        }}, re.removeEvent = he.removeEventListener ? function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function (e, t, n) {
        var i = "on" + t;
        e.detachEvent && (typeof e[i] === Te && (e[i] = null), e.detachEvent(i, n))
    }, re.Event = function (e, t) {
        return this instanceof re.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && e.returnValue === !1 ? p : f) : this.type = e, t && re.extend(this, t), this.timeStamp = e && e.timeStamp || re.now(), void(this[re.expando] = !0)) : new re.Event(e, t)
    }, re.Event.prototype = {isDefaultPrevented: f, isPropagationStopped: f, isImmediatePropagationStopped: f, preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = p, e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        }, stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = p, e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        }, stopImmediatePropagation: function () {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = p, e && e.stopImmediatePropagation && e.stopImmediatePropagation(), this.stopPropagation()
        }}, re.each({mouseenter: "mouseover", mouseleave: "mouseout", pointerenter: "pointerover", pointerleave: "pointerout"}, function (e, t) {
        re.event.special[e] = {delegateType: t, bindType: t, handle: function (e) {
                var n, i = this, r = e.relatedTarget, o = e.handleObj;
                return(!r || r !== i && !re.contains(i, r)) && (e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t), n
            }}
    }), ne.submitBubbles || (re.event.special.submit = {setup: function () {
            return re.nodeName(this, "form") ? !1 : void re.event.add(this, "click._submit keypress._submit", function (e) {
                var t = e.target, n = re.nodeName(t, "input") || re.nodeName(t, "button") ? t.form : void 0;
                n && !re._data(n, "submitBubbles") && (re.event.add(n, "submit._submit", function (e) {
                    e._submit_bubble = !0
                }), re._data(n, "submitBubbles", !0))
            })
        }, postDispatch: function (e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && re.event.simulate("submit", this.parentNode, e, !0))
        }, teardown: function () {
            return re.nodeName(this, "form") ? !1 : void re.event.remove(this, "._submit")
        }}), ne.changeBubbles || (re.event.special.change = {setup: function () {
            return _e.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (re.event.add(this, "propertychange._change", function (e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), re.event.add(this, "click._change", function (e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1), re.event.simulate("change", this, e, !0)
            })), !1) : void re.event.add(this, "beforeactivate._change", function (e) {
                var t = e.target;
                _e.test(t.nodeName) && !re._data(t, "changeBubbles") && (re.event.add(t, "change._change", function (e) {
                    !this.parentNode || e.isSimulated || e.isTrigger || re.event.simulate("change", this.parentNode, e, !0)
                }), re._data(t, "changeBubbles", !0))
            })
        }, handle: function (e) {
            var t = e.target;
            return this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type ? e.handleObj.handler.apply(this, arguments) : void 0
        }, teardown: function () {
            return re.event.remove(this, "._change"), !_e.test(this.nodeName)
        }}), ne.focusinBubbles || re.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = function (e) {
            re.event.simulate(t, e.target, re.event.fix(e), !0)
        };
        re.event.special[t] = {setup: function () {
                var i = this.ownerDocument || this, r = re._data(i, t);
                r || i.addEventListener(e, n, !0), re._data(i, t, (r || 0) + 1)
            }, teardown: function () {
                var i = this.ownerDocument || this, r = re._data(i, t) - 1;
                r ? re._data(i, t, r) : (i.removeEventListener(e, n, !0), re._removeData(i, t))
            }}
    }), re.fn.extend({on: function (e, t, n, i, r) {
            var o, s;
            if ("object" == typeof e) {
                "string" != typeof t && (n = n || t, t = void 0);
                for (o in e)
                    this.on(o, t, n, e[o], r);
                return this
            }
            if (null == n && null == i ? (i = t, n = t = void 0) : null == i && ("string" == typeof t ? (i = n, n = void 0) : (i = n, n = t, t = void 0)), i === !1)
                i = f;
            else if (!i)
                return this;
            return 1 === r && (s = i, i = function (e) {
                return re().off(e), s.apply(this, arguments)
            }, i.guid = s.guid || (s.guid = re.guid++)), this.each(function () {
                re.event.add(this, e, i, n, t)
            })
        }, one: function (e, t, n, i) {
            return this.on(e, t, n, i, 1)
        }, off: function (e, t, n) {
            var i, r;
            if (e && e.preventDefault && e.handleObj)
                return i = e.handleObj, re(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
            if ("object" == typeof e) {
                for (r in e)
                    this.off(r, t, e[r]);
                return this
            }
            return(t === !1 || "function" == typeof t) && (n = t, t = void 0), n === !1 && (n = f), this.each(function () {
                re.event.remove(this, e, n, t)
            })
        }, trigger: function (e, t) {
            return this.each(function () {
                re.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            var n = this[0];
            return n ? re.event.trigger(e, t, n, !0) : void 0
        }});
    var He = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video", qe = / jQuery\d+="(?:null|\d+)"/g, Fe = new RegExp("<(?:" + He + ")[\\s/>]", "i"), Oe = /^\s+/, Pe = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, Ie = /<([\w:]+)/, ze = /<tbody/i, Be = /<|&#?\w+;/, Re = /<(?:script|style|link)/i, Xe = /checked\s*(?:[^=]|=\s*.checked.)/i, Ve = /^$|\/(?:java|ecma)script/i, Qe = /^true\/(.*)/, Ue = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, Ye = {option: [1, "<select multiple='multiple'>", "</select>"], legend: [1, "<fieldset>", "</fieldset>"], area: [1, "<map>", "</map>"], param: [1, "<object>", "</object>"], thead: [1, "<table>", "</table>"], tr: [2, "<table><tbody>", "</tbody></table>"], col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"], td: [3, "<table><tbody><tr>", "</tr></tbody></table>"], _default: ne.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]}, Je = g(he), Ge = Je.appendChild(he.createElement("div"));
    Ye.optgroup = Ye.option, Ye.tbody = Ye.tfoot = Ye.colgroup = Ye.caption = Ye.thead, Ye.th = Ye.td, re.extend({clone: function (e, t, n) {
            var i, r, o, s, a, l = re.contains(e.ownerDocument, e);
            if (ne.html5Clone || re.isXMLDoc(e) || !Fe.test("<" + e.nodeName + ">") ? o = e.cloneNode(!0) : (Ge.innerHTML = e.outerHTML, Ge.removeChild(o = Ge.firstChild)), !(ne.noCloneEvent && ne.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || re.isXMLDoc(e)))
                for (i = m(o), a = m(e), s = 0; null != (r = a[s]); ++s)
                    i[s] && T(r, i[s]);
            if (t)
                if (n)
                    for (a = a || m(e), i = i || m(o), s = 0; null != (r = a[s]); s++)
                        S(r, i[s]);
                else
                    S(e, o);
            return i = m(o, "script"), i.length > 0 && w(i, !l && m(e, "script")), i = a = r = null, o
        }, buildFragment: function (e, t, n, i) {
            for (var r, o, s, a, l, c, u, d = e.length, p = g(t), f = [], h = 0; d > h; h++)
                if (o = e[h], o || 0 === o)
                    if ("object" === re.type(o))
                        re.merge(f, o.nodeType ? [o] : o);
                    else if (Be.test(o)) {
                        for (a = a || p.appendChild(t.createElement("div")), l = (Ie.exec(o) || ["", ""])[1].toLowerCase(), u = Ye[l] || Ye._default, a.innerHTML = u[1] + o.replace(Pe, "<$1></$2>") + u[2], r = u[0]; r--; )
                            a = a.lastChild;
                        if (!ne.leadingWhitespace && Oe.test(o) && f.push(t.createTextNode(Oe.exec(o)[0])), !ne.tbody)
                            for (o = "table" !== l || ze.test(o)?"<table>" !== u[1] || ze.test(o)?0:a:a.firstChild, r = o && o.childNodes.length; r--; )
                                re.nodeName(c = o.childNodes[r], "tbody") && !c.childNodes.length && o.removeChild(c);
                        for (re.merge(f, a.childNodes), a.textContent = ""; a.firstChild; )
                            a.removeChild(a.firstChild);
                        a = p.lastChild
                    } else
                        f.push(t.createTextNode(o));
            for (a && p.removeChild(a), ne.appendChecked || re.grep(m(f, "input"), v), h = 0; o = f[h++]; )
                if ((!i || -1 === re.inArray(o, i)) && (s = re.contains(o.ownerDocument, o), a = m(p.appendChild(o), "script"), s && w(a), n))
                    for (r = 0; o = a[r++]; )
                        Ve.test(o.type || "") && n.push(o);
            return a = null, p
        }, cleanData: function (e, t) {
            for (var n, i, r, o, s = 0, a = re.expando, l = re.cache, c = ne.deleteExpando, u = re.event.special; null != (n = e[s]); s++)
                if ((t || re.acceptData(n)) && (r = n[a], o = r && l[r])) {
                    if (o.events)
                        for (i in o.events)
                            u[i] ? re.event.remove(n, i) : re.removeEvent(n, i, o.handle);
                    l[r] && (delete l[r], c ? delete n[a] : typeof n.removeAttribute !== Te ? n.removeAttribute(a) : n[a] = null, U.push(r))
                }
        }}), re.fn.extend({text: function (e) {
            return Ae(this, function (e) {
                return void 0 === e ? re.text(this) : this.empty().append((this[0] && this[0].ownerDocument || he).createTextNode(e))
            }, null, e, arguments.length)
        }, append: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.appendChild(e)
                }
            })
        }, prepend: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        }, before: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        }, after: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        }, remove: function (e, t) {
            for (var n, i = e ? re.filter(e, this) : this, r = 0; null != (n = i[r]); r++)
                t || 1 !== n.nodeType || re.cleanData(m(n)), n.parentNode && (t && re.contains(n.ownerDocument, n) && w(m(n, "script")), n.parentNode.removeChild(n));
            return this
        }, empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++) {
                for (1 === e.nodeType && re.cleanData(m(e, !1)); e.firstChild; )
                    e.removeChild(e.firstChild);
                e.options && re.nodeName(e, "select") && (e.options.length = 0)
            }
            return this
        }, clone: function (e, t) {
            return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function () {
                return re.clone(this, e, t)
            })
        }, html: function (e) {
            return Ae(this, function (e) {
                var t = this[0] || {}, n = 0, i = this.length;
                if (void 0 === e)
                    return 1 === t.nodeType ? t.innerHTML.replace(qe, "") : void 0;
                if ("string" == typeof e && !Re.test(e) && (ne.htmlSerialize || !Fe.test(e)) && (ne.leadingWhitespace || !Oe.test(e)) && !Ye[(Ie.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = e.replace(Pe, "<$1></$2>");
                    try {
                        for (; i > n; n++)
                            t = this[n] || {}, 1 === t.nodeType && (re.cleanData(m(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (r) {
                    }
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function () {
            var e = arguments[0];
            return this.domManip(arguments, function (t) {
                e = this.parentNode, re.cleanData(m(this)), e && e.replaceChild(t, this)
            }), e && (e.length || e.nodeType) ? this : this.remove()
        }, detach: function (e) {
            return this.remove(e, !0)
        }, domManip: function (e, t) {
            e = J.apply([], e);
            var n, i, r, o, s, a, l = 0, c = this.length, u = this, d = c - 1, p = e[0], f = re.isFunction(p);
            if (f || c > 1 && "string" == typeof p && !ne.checkClone && Xe.test(p))
                return this.each(function (n) {
                    var i = u.eq(n);
                    f && (e[0] = p.call(this, n, i.html())), i.domManip(e, t)
                });
            if (c && (a = re.buildFragment(e, this[0].ownerDocument, !1, this), n = a.firstChild, 1 === a.childNodes.length && (a = n), n)) {
                for (o = re.map(m(a, "script"), x), r = o.length; c > l; l++)
                    i = a, l !== d && (i = re.clone(i, !0, !0), r && re.merge(o, m(i, "script"))), t.call(this[l], i, l);
                if (r)
                    for (s = o[o.length - 1].ownerDocument, re.map(o, b), l = 0; r > l; l++)
                        i = o[l], Ve.test(i.type || "") && !re._data(i, "globalEval") && re.contains(s, i) && (i.src ? re._evalUrl && re._evalUrl(i.src) : re.globalEval((i.text || i.textContent || i.innerHTML || "").replace(Ue, "")));
                a = n = null
            }
            return this
        }}), re.each({appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith"}, function (e, t) {
        re.fn[e] = function (e) {
            for (var n, i = 0, r = [], o = re(e), s = o.length - 1; s >= i; i++)
                n = i === s ? this : this.clone(!0), re(o[i])[t](n), G.apply(r, n.get());
            return this.pushStack(r)
        }
    });
    var Ke, Ze = {};
    !function () {
        var e;
        ne.shrinkWrapBlocks = function () {
            if (null != e)
                return e;
            e = !1;
            var t, n, i;
            return n = he.getElementsByTagName("body")[0], n && n.style ? (t = he.createElement("div"), i = he.createElement("div"), i.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(i).appendChild(t), typeof t.style.zoom !== Te && (t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", t.appendChild(he.createElement("div")).style.width = "5px", e = 3 !== t.offsetWidth), n.removeChild(i), e) : void 0
        }
    }();
    var et, tt, nt = /^margin/, it = new RegExp("^(" + ke + ")(?!px)[a-z%]+$", "i"), rt = /^(top|right|bottom|left)$/;
    e.getComputedStyle ? (et = function (t) {
        return t.ownerDocument.defaultView.opener ? t.ownerDocument.defaultView.getComputedStyle(t, null) : e.getComputedStyle(t, null)
    }, tt = function (e, t, n) {
        var i, r, o, s, a = e.style;
        return n = n || et(e), s = n ? n.getPropertyValue(t) || n[t] : void 0, n && ("" !== s || re.contains(e.ownerDocument, e) || (s = re.style(e, t)), it.test(s) && nt.test(t) && (i = a.width, r = a.minWidth, o = a.maxWidth, a.minWidth = a.maxWidth = a.width = s, s = n.width, a.width = i, a.minWidth = r, a.maxWidth = o)), void 0 === s ? s : s + ""
    }) : he.documentElement.currentStyle && (et = function (e) {
        return e.currentStyle
    }, tt = function (e, t, n) {
        var i, r, o, s, a = e.style;
        return n = n || et(e), s = n ? n[t] : void 0, null == s && a && a[t] && (s = a[t]), it.test(s) && !rt.test(t) && (i = a.left, r = e.runtimeStyle, o = r && r.left, o && (r.left = e.currentStyle.left), a.left = "fontSize" === t ? "1em" : s, s = a.pixelLeft + "px", a.left = i, o && (r.left = o)), void 0 === s ? s : s + "" || "auto"
    }), function () {
        function t() {
            var t, n, i, r;
            n = he.getElementsByTagName("body")[0], n && n.style && (t = he.createElement("div"), i = he.createElement("div"), i.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(i).appendChild(t), t.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", o = s = !1, l = !0, e.getComputedStyle && (o = "1%" !== (e.getComputedStyle(t, null) || {}).top, s = "4px" === (e.getComputedStyle(t, null) || {width: "4px"}).width, r = t.appendChild(he.createElement("div")), r.style.cssText = t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", r.style.marginRight = r.style.width = "0", t.style.width = "1px", l = !parseFloat((e.getComputedStyle(r, null) || {}).marginRight), t.removeChild(r)), t.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", r = t.getElementsByTagName("td"), r[0].style.cssText = "margin:0;border:0;padding:0;display:none", a = 0 === r[0].offsetHeight, a && (r[0].style.display = "", r[1].style.display = "none", a = 0 === r[0].offsetHeight), n.removeChild(i))
        }
        var n, i, r, o, s, a, l;
        n = he.createElement("div"), n.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", r = n.getElementsByTagName("a")[0], i = r && r.style, i && (i.cssText = "float:left;opacity:.5", ne.opacity = "0.5" === i.opacity, ne.cssFloat = !!i.cssFloat, n.style.backgroundClip = "content-box", n.cloneNode(!0).style.backgroundClip = "", ne.clearCloneStyle = "content-box" === n.style.backgroundClip, ne.boxSizing = "" === i.boxSizing || "" === i.MozBoxSizing || "" === i.WebkitBoxSizing, re.extend(ne, {reliableHiddenOffsets: function () {
                return null == a && t(), a
            }, boxSizingReliable: function () {
                return null == s && t(), s
            }, pixelPosition: function () {
                return null == o && t(), o
            }, reliableMarginRight: function () {
                return null == l && t(), l
            }}))
    }(), re.swap = function (e, t, n, i) {
        var r, o, s = {};
        for (o in t)
            s[o] = e.style[o], e.style[o] = t[o];
        r = n.apply(e, i || []);
        for (o in t)
            e.style[o] = s[o];
        return r
    };
    var ot = /alpha\([^)]*\)/i, st = /opacity\s*=\s*([^)]*)/, at = /^(none|table(?!-c[ea]).+)/, lt = new RegExp("^(" + ke + ")(.*)$", "i"), ct = new RegExp("^([+-])=(" + ke + ")", "i"), ut = {position: "absolute", visibility: "hidden", display: "block"}, dt = {letterSpacing: "0", fontWeight: "400"}, pt = ["Webkit", "O", "Moz", "ms"];
    re.extend({cssHooks: {opacity: {get: function (e, t) {
                    if (t) {
                        var n = tt(e, "opacity");
                        return"" === n ? "1" : n
                    }
                }}}, cssNumber: {columnCount: !0, fillOpacity: !0, flexGrow: !0, flexShrink: !0, fontWeight: !0, lineHeight: !0, opacity: !0, order: !0, orphans: !0, widows: !0, zIndex: !0, zoom: !0}, cssProps: {"float": ne.cssFloat ? "cssFloat" : "styleFloat"}, style: function (e, t, n, i) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var r, o, s, a = re.camelCase(t), l = e.style;
                if (t = re.cssProps[a] || (re.cssProps[a] = N(l, a)), s = re.cssHooks[t] || re.cssHooks[a], void 0 === n)
                    return s && "get"in s && void 0 !== (r = s.get(e, !1, i)) ? r : l[t];
                if (o = typeof n, "string" === o && (r = ct.exec(n)) && (n = (r[1] + 1) * r[2] + parseFloat(re.css(e, t)), o = "number"), null != n && n === n && ("number" !== o || re.cssNumber[a] || (n += "px"), ne.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (l[t] = "inherit"), !(s && "set"in s && void 0 === (n = s.set(e, n, i)))))
                    try {
                        l[t] = n
                    } catch (c) {
                    }
            }
        }, css: function (e, t, n, i) {
            var r, o, s, a = re.camelCase(t);
            return t = re.cssProps[a] || (re.cssProps[a] = N(e.style, a)), s = re.cssHooks[t] || re.cssHooks[a], s && "get"in s && (o = s.get(e, !0, n)), void 0 === o && (o = tt(e, t, i)), "normal" === o && t in dt && (o = dt[t]), "" === n || n ? (r = parseFloat(o), n === !0 || re.isNumeric(r) ? r || 0 : o) : o
        }}), re.each(["height", "width"], function (e, t) {
        re.cssHooks[t] = {get: function (e, n, i) {
                return n ? at.test(re.css(e, "display")) && 0 === e.offsetWidth ? re.swap(e, ut, function () {
                    return _(e, t, i)
                }) : _(e, t, i) : void 0
            }, set: function (e, n, i) {
                var r = i && et(e);
                return A(e, n, i ? W(e, t, i, ne.boxSizing && "border-box" === re.css(e, "boxSizing", !1, r), r) : 0)
            }}
    }), ne.opacity || (re.cssHooks.opacity = {get: function (e, t) {
            return st.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        }, set: function (e, t) {
            var n = e.style, i = e.currentStyle, r = re.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "", o = i && i.filter || n.filter || "";
            n.zoom = 1, (t >= 1 || "" === t) && "" === re.trim(o.replace(ot, "")) && n.removeAttribute && (n.removeAttribute("filter"), "" === t || i && !i.filter) || (n.filter = ot.test(o) ? o.replace(ot, r) : o + " " + r)
        }}), re.cssHooks.marginRight = k(ne.reliableMarginRight, function (e, t) {
        return t ? re.swap(e, {display: "inline-block"}, tt, [e, "marginRight"]) : void 0
    }), re.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        re.cssHooks[e + t] = {expand: function (n) {
                for (var i = 0, r = {}, o = "string" == typeof n ? n.split(" ") : [n]; 4 > i; i++)
                    r[e + Ne[i] + t] = o[i] || o[i - 2] || o[0];
                return r
            }}, nt.test(e) || (re.cssHooks[e + t].set = A)
    }), re.fn.extend({css: function (e, t) {
            return Ae(this, function (e, t, n) {
                var i, r, o = {}, s = 0;
                if (re.isArray(t)) {
                    for (i = et(e), r = t.length; r > s; s++)
                        o[t[s]] = re.css(e, t[s], !1, i);
                    return o
                }
                return void 0 !== n ? re.style(e, t, n) : re.css(e, t)
            }, e, t, arguments.length > 1)
        }, show: function () {
            return $(this, !0)
        }, hide: function () {
            return $(this)
        }, toggle: function (e) {
            return"boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                $e(this) ? re(this).show() : re(this).hide()
            })
        }}), re.Tween = D, D.prototype = {constructor: D, init: function (e, t, n, i, r, o) {
            this.elem = e, this.prop = n, this.easing = r || "swing", this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = o || (re.cssNumber[n] ? "" : "px")
        }, cur: function () {
            var e = D.propHooks[this.prop];
            return e && e.get ? e.get(this) : D.propHooks._default.get(this)
        }, run: function (e) {
            var t, n = D.propHooks[this.prop];
            return this.options.duration ? this.pos = t = re.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : D.propHooks._default.set(this), this
        }}, D.prototype.init.prototype = D.prototype, D.propHooks = {_default: {get: function (e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = re.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0) : e.elem[e.prop]
            }, set: function (e) {
                re.fx.step[e.prop] ? re.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[re.cssProps[e.prop]] || re.cssHooks[e.prop]) ? re.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }}}, D.propHooks.scrollTop = D.propHooks.scrollLeft = {set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }}, re.easing = {linear: function (e) {
            return e
        }, swing: function (e) {
            return.5 - Math.cos(e * Math.PI) / 2
        }}, re.fx = D.prototype.init, re.fx.step = {};
    var ft, ht, gt = /^(?:toggle|show|hide)$/, mt = new RegExp("^(?:([+-])=|)(" + ke + ")([a-z%]*)$", "i"), vt = /queueHooks$/, yt = [H], xt = {"*": [function (e, t) {
                var n = this.createTween(e, t), i = n.cur(), r = mt.exec(t), o = r && r[3] || (re.cssNumber[e] ? "" : "px"), s = (re.cssNumber[e] || "px" !== o && +i) && mt.exec(re.css(n.elem, e)), a = 1, l = 20;
                if (s && s[3] !== o) {
                    o = o || s[3], r = r || [], s = +i || 1;
                    do
                        a = a || ".5", s /= a, re.style(n.elem, e, s + o);
                    while (a !== (a = n.cur() / i) && 1 !== a && --l)
                }
                return r && (s = n.start = +s || +i || 0, n.unit = o, n.end = r[1] ? s + (r[1] + 1) * r[2] : +r[2]), n
            }]};
    re.Animation = re.extend(F, {tweener: function (e, t) {
            re.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            for (var n, i = 0, r = e.length; r > i; i++)
                n = e[i], xt[n] = xt[n] || [], xt[n].unshift(t)
        }, prefilter: function (e, t) {
            t ? yt.unshift(e) : yt.push(e)
        }}), re.speed = function (e, t, n) {
        var i = e && "object" == typeof e ? re.extend({}, e) : {complete: n || !n && t || re.isFunction(e) && e, duration: e, easing: n && t || t && !re.isFunction(t) && t};
        return i.duration = re.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in re.fx.speeds ? re.fx.speeds[i.duration] : re.fx.speeds._default, (null == i.queue || i.queue === !0) && (i.queue = "fx"), i.old = i.complete, i.complete = function () {
            re.isFunction(i.old) && i.old.call(this), i.queue && re.dequeue(this, i.queue)
        }, i
    }, re.fn.extend({fadeTo: function (e, t, n, i) {
            return this.filter($e).css("opacity", 0).show().end().animate({opacity: t}, e, n, i)
        }, animate: function (e, t, n, i) {
            var r = re.isEmptyObject(e), o = re.speed(t, n, i), s = function () {
                var t = F(this, re.extend({}, e), o);
                (r || re._data(this, "finish")) && t.stop(!0)
            };
            return s.finish = s, r || o.queue === !1 ? this.each(s) : this.queue(o.queue, s)
        }, stop: function (e, t, n) {
            var i = function (e) {
                var t = e.stop;
                delete e.stop, t(n)
            };
            return"string" != typeof e && (n = t, t = e, e = void 0), t && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                var t = !0, r = null != e && e + "queueHooks", o = re.timers, s = re._data(this);
                if (r)
                    s[r] && s[r].stop && i(s[r]);
                else
                    for (r in s)
                        s[r] && s[r].stop && vt.test(r) && i(s[r]);
                for (r = o.length; r--; )
                    o[r].elem !== this || null != e && o[r].queue !== e || (o[r].anim.stop(n), t = !1, o.splice(r, 1));
                (t || !n) && re.dequeue(this, e)
            })
        }, finish: function (e) {
            return e !== !1 && (e = e || "fx"), this.each(function () {
                var t, n = re._data(this), i = n[e + "queue"], r = n[e + "queueHooks"], o = re.timers, s = i ? i.length : 0;
                for (n.finish = !0, re.queue(this, e, []), r && r.stop && r.stop.call(this, !0), t = o.length; t--; )
                    o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                for (t = 0; s > t; t++)
                    i[t] && i[t].finish && i[t].finish.call(this);
                delete n.finish
            })
        }}), re.each(["toggle", "show", "hide"], function (e, t) {
        var n = re.fn[t];
        re.fn[t] = function (e, i, r) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(L(t, !0), e, i, r)
        }
    }), re.each({slideDown: L("show"), slideUp: L("hide"), slideToggle: L("toggle"), fadeIn: {opacity: "show"}, fadeOut: {opacity: "hide"}, fadeToggle: {opacity: "toggle"}}, function (e, t) {
        re.fn[e] = function (e, n, i) {
            return this.animate(t, e, n, i)
        }
    }), re.timers = [], re.fx.tick = function () {
        var e, t = re.timers, n = 0;
        for (ft = re.now(); n < t.length; n++)
            e = t[n], e() || t[n] !== e || t.splice(n--, 1);
        t.length || re.fx.stop(), ft = void 0
    }, re.fx.timer = function (e) {
        re.timers.push(e), e() ? re.fx.start() : re.timers.pop()
    }, re.fx.interval = 13, re.fx.start = function () {
        ht || (ht = setInterval(re.fx.tick, re.fx.interval))
    }, re.fx.stop = function () {
        clearInterval(ht), ht = null
    }, re.fx.speeds = {slow: 600, fast: 200, _default: 400}, re.fn.delay = function (e, t) {
        return e = re.fx ? re.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, n) {
            var i = setTimeout(t, e);
            n.stop = function () {
                clearTimeout(i)
            }
        })
    }, function () {
        var e, t, n, i, r;
        t = he.createElement("div"), t.setAttribute("className", "t"), t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", i = t.getElementsByTagName("a")[0], n = he.createElement("select"), r = n.appendChild(he.createElement("option")), e = t.getElementsByTagName("input")[0], i.style.cssText = "top:1px", ne.getSetAttribute = "t" !== t.className, ne.style = /top/.test(i.getAttribute("style")), ne.hrefNormalized = "/a" === i.getAttribute("href"), ne.checkOn = !!e.value, ne.optSelected = r.selected, ne.enctype = !!he.createElement("form").enctype, n.disabled = !0, ne.optDisabled = !r.disabled, e = he.createElement("input"), e.setAttribute("value", ""), ne.input = "" === e.getAttribute("value"), e.value = "t", e.setAttribute("type", "radio"), ne.radioValue = "t" === e.value
    }();
    var bt = /\r/g;
    re.fn.extend({val: function (e) {
            var t, n, i, r = this[0];
            {
                if (arguments.length)
                    return i = re.isFunction(e), this.each(function (n) {
                        var r;
                        1 === this.nodeType && (r = i ? e.call(this, n, re(this).val()) : e, null == r ? r = "" : "number" == typeof r ? r += "" : re.isArray(r) && (r = re.map(r, function (e) {
                            return null == e ? "" : e + ""
                        })), t = re.valHooks[this.type] || re.valHooks[this.nodeName.toLowerCase()], t && "set"in t && void 0 !== t.set(this, r, "value") || (this.value = r))
                    });
                if (r)
                    return t = re.valHooks[r.type] || re.valHooks[r.nodeName.toLowerCase()], t && "get"in t && void 0 !== (n = t.get(r, "value")) ? n : (n = r.value, "string" == typeof n ? n.replace(bt, "") : null == n ? "" : n)
            }
        }}), re.extend({valHooks: {option: {get: function (e) {
                    var t = re.find.attr(e, "value");
                    return null != t ? t : re.trim(re.text(e))
                }}, select: {get: function (e) {
                    for (var t, n, i = e.options, r = e.selectedIndex, o = "select-one" === e.type || 0 > r, s = o ? null : [], a = o ? r + 1 : i.length, l = 0 > r ? a : o ? r : 0; a > l; l++)
                        if (n = i[l], (n.selected || l === r) && (ne.optDisabled ? !n.disabled : null === n.getAttribute("disabled")) && (!n.parentNode.disabled || !re.nodeName(n.parentNode, "optgroup"))) {
                            if (t = re(n).val(), o)
                                return t;
                            s.push(t)
                        }
                    return s
                }, set: function (e, t) {
                    for (var n, i, r = e.options, o = re.makeArray(t), s = r.length; s--; )
                        if (i = r[s], re.inArray(re.valHooks.option.get(i), o) >= 0)
                            try {
                                i.selected = n = !0
                            } catch (a) {
                                i.scrollHeight
                            }
                        else
                            i.selected = !1;
                    return n || (e.selectedIndex = -1), r
                }}}}), re.each(["radio", "checkbox"], function () {
        re.valHooks[this] = {set: function (e, t) {
                return re.isArray(t) ? e.checked = re.inArray(re(e).val(), t) >= 0 : void 0
            }}, ne.checkOn || (re.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var wt, St, Tt = re.expr.attrHandle, Et = /^(?:checked|selected)$/i, Ct = ne.getSetAttribute, kt = ne.input;
    re.fn.extend({attr: function (e, t) {
            return Ae(this, re.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                re.removeAttr(this, e)
            })
        }}), re.extend({attr: function (e, t, n) {
            var i, r, o = e.nodeType;
            if (e && 3 !== o && 8 !== o && 2 !== o)
                return typeof e.getAttribute === Te ? re.prop(e, t, n) : (1 === o && re.isXMLDoc(e) || (t = t.toLowerCase(), i = re.attrHooks[t] || (re.expr.match.bool.test(t) ? St : wt)), void 0 === n ? i && "get"in i && null !== (r = i.get(e, t)) ? r : (r = re.find.attr(e, t), null == r ? void 0 : r) : null !== n ? i && "set"in i && void 0 !== (r = i.set(e, n, t)) ? r : (e.setAttribute(t, n + ""), n) : void re.removeAttr(e, t))
        }, removeAttr: function (e, t) {
            var n, i, r = 0, o = t && t.match(xe);
            if (o && 1 === e.nodeType)
                for (; n = o[r++]; )
                    i = re.propFix[n] || n, re.expr.match.bool.test(n) ? kt && Ct || !Et.test(n) ? e[i] = !1 : e[re.camelCase("default-" + n)] = e[i] = !1 : re.attr(e, n, ""), e.removeAttribute(Ct ? n : i)
        }, attrHooks: {type: {set: function (e, t) {
                    if (!ne.radioValue && "radio" === t && re.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }}}}), St = {set: function (e, t, n) {
            return t === !1 ? re.removeAttr(e, n) : kt && Ct || !Et.test(n) ? e.setAttribute(!Ct && re.propFix[n] || n, n) : e[re.camelCase("default-" + n)] = e[n] = !0, n
        }}, re.each(re.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var n = Tt[t] || re.find.attr;
        Tt[t] = kt && Ct || !Et.test(t) ? function (e, t, i) {
            var r, o;
            return i || (o = Tt[t], Tt[t] = r, r = null != n(e, t, i) ? t.toLowerCase() : null, Tt[t] = o), r
        } : function (e, t, n) {
            return n ? void 0 : e[re.camelCase("default-" + t)] ? t.toLowerCase() : null
        }
    }), kt && Ct || (re.attrHooks.value = {set: function (e, t, n) {
            return re.nodeName(e, "input") ? void(e.defaultValue = t) : wt && wt.set(e, t, n)
        }}), Ct || (wt = {set: function (e, t, n) {
            var i = e.getAttributeNode(n);
            return i || e.setAttributeNode(i = e.ownerDocument.createAttribute(n)), i.value = t += "", "value" === n || t === e.getAttribute(n) ? t : void 0
        }}, Tt.id = Tt.name = Tt.coords = function (e, t, n) {
        var i;
        return n ? void 0 : (i = e.getAttributeNode(t)) && "" !== i.value ? i.value : null
    }, re.valHooks.button = {get: function (e, t) {
            var n = e.getAttributeNode(t);
            return n && n.specified ? n.value : void 0
        }, set: wt.set}, re.attrHooks.contenteditable = {set: function (e, t, n) {
            wt.set(e, "" === t ? !1 : t, n)
        }}, re.each(["width", "height"], function (e, t) {
        re.attrHooks[t] = {set: function (e, n) {
                return"" === n ? (e.setAttribute(t, "auto"), n) : void 0
            }}
    })), ne.style || (re.attrHooks.style = {get: function (e) {
            return e.style.cssText || void 0
        }, set: function (e, t) {
            return e.style.cssText = t + ""
        }});
    var Nt = /^(?:input|select|textarea|button|object)$/i, $t = /^(?:a|area)$/i;
    re.fn.extend({prop: function (e, t) {
            return Ae(this, re.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return e = re.propFix[e] || e, this.each(function () {
                try {
                    this[e] = void 0, delete this[e]
                } catch (t) {
                }
            })
        }}), re.extend({propFix: {"for": "htmlFor", "class": "className"}, prop: function (e, t, n) {
            var i, r, o, s = e.nodeType;
            if (e && 3 !== s && 8 !== s && 2 !== s)
                return o = 1 !== s || !re.isXMLDoc(e), o && (t = re.propFix[t] || t, r = re.propHooks[t]), void 0 !== n ? r && "set"in r && void 0 !== (i = r.set(e, n, t)) ? i : e[t] = n : r && "get"in r && null !== (i = r.get(e, t)) ? i : e[t]
        }, propHooks: {tabIndex: {get: function (e) {
                    var t = re.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : Nt.test(e.nodeName) || $t.test(e.nodeName) && e.href ? 0 : -1
                }}}}), ne.hrefNormalized || re.each(["href", "src"], function (e, t) {
        re.propHooks[t] = {get: function (e) {
                return e.getAttribute(t, 4)
            }}
    }), ne.optSelected || (re.propHooks.selected = {get: function (e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
        }}), re.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        re.propFix[this.toLowerCase()] = this
    }), ne.enctype || (re.propFix.enctype = "encoding");
    var At = /[\t\r\n\f]/g;
    re.fn.extend({addClass: function (e) {
            var t, n, i, r, o, s, a = 0, l = this.length, c = "string" == typeof e && e;
            if (re.isFunction(e))
                return this.each(function (t) {
                    re(this).addClass(e.call(this, t, this.className))
                });
            if (c)
                for (t = (e || "").match(xe) || []; l > a; a++)
                    if (n = this[a], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(At, " ") : " ")) {
                        for (o = 0; r = t[o++]; )
                            i.indexOf(" " + r + " ") < 0 && (i += r + " ");
                        s = re.trim(i), n.className !== s && (n.className = s)
                    }
            return this
        }, removeClass: function (e) {
            var t, n, i, r, o, s, a = 0, l = this.length, c = 0 === arguments.length || "string" == typeof e && e;
            if (re.isFunction(e))
                return this.each(function (t) {
                    re(this).removeClass(e.call(this, t, this.className))
                });
            if (c)
                for (t = (e || "").match(xe) || []; l > a; a++)
                    if (n = this[a], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(At, " ") : "")) {
                        for (o = 0; r = t[o++]; )
                            for (; i.indexOf(" " + r + " ") >= 0; )
                                i = i.replace(" " + r + " ", " ");
                        s = e ? re.trim(i) : "", n.className !== s && (n.className = s)
                    }
            return this
        }, toggleClass: function (e, t) {
            var n = typeof e;
            return"boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : re.isFunction(e) ? this.each(function (n) {
                re(this).toggleClass(e.call(this, n, this.className, t), t)
            }) : this.each(function () {
                if ("string" === n)
                    for (var t, i = 0, r = re(this), o = e.match(xe) || []; t = o[i++]; )
                        r.hasClass(t) ? r.removeClass(t) : r.addClass(t);
                else
                    (n === Te || "boolean" === n) && (this.className && re._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : re._data(this, "__className__") || "")
            })
        }, hasClass: function (e) {
            for (var t = " " + e + " ", n = 0, i = this.length; i > n; n++)
                if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(At, " ").indexOf(t) >= 0)
                    return!0;
            return!1
        }}), re.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        re.fn[t] = function (e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), re.fn.extend({hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }, bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, delegate: function (e, t, n, i) {
            return this.on(t, e, n, i)
        }, undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }});
    var Wt = re.now(), _t = /\?/, Dt = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    re.parseJSON = function (t) {
        if (e.JSON && e.JSON.parse)
            return e.JSON.parse(t + "");
        var n, i = null, r = re.trim(t + "");
        return r && !re.trim(r.replace(Dt, function (e, t, r, o) {
            return n && t && (i = 0), 0 === i ? e : (n = r || t, i += !o - !r, "")
        })) ? Function("return " + r)() : re.error("Invalid JSON: " + t)
    }, re.parseXML = function (t) {
        var n, i;
        if (!t || "string" != typeof t)
            return null;
        try {
            e.DOMParser ? (i = new DOMParser, n = i.parseFromString(t, "text/xml")) : (n = new ActiveXObject("Microsoft.XMLDOM"), n.async = "false", n.loadXML(t))
        } catch (r) {
            n = void 0
        }
        return n && n.documentElement && !n.getElementsByTagName("parsererror").length || re.error("Invalid XML: " + t), n
    };
    var jt, Lt, Mt = /#.*$/, Ht = /([?&])_=[^&]*/, qt = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, Ft = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, Ot = /^(?:GET|HEAD)$/, Pt = /^\/\//, It = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, zt = {}, Bt = {}, Rt = "*/".concat("*");
    try {
        Lt = location.href
    } catch (Xt) {
        Lt = he.createElement("a"), Lt.href = "", Lt = Lt.href
    }
    jt = It.exec(Lt.toLowerCase()) || [], re.extend({active: 0, lastModified: {}, etag: {}, ajaxSettings: {url: Lt, type: "GET", isLocal: Ft.test(jt[1]), global: !0, processData: !0, async: !0, contentType: "application/x-www-form-urlencoded; charset=UTF-8", accepts: {"*": Rt, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript"}, contents: {xml: /xml/, html: /html/, json: /json/}, responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"}, converters: {"* text": String, "text html": !0, "text json": re.parseJSON, "text xml": re.parseXML}, flatOptions: {url: !0, context: !0}}, ajaxSetup: function (e, t) {
            return t ? I(I(e, re.ajaxSettings), t) : I(re.ajaxSettings, e)
        }, ajaxPrefilter: O(zt), ajaxTransport: O(Bt), ajax: function (e, t) {
            function n(e, t, n, i) {
                var r, u, v, y, b, S = t;
                2 !== x && (x = 2, a && clearTimeout(a), c = void 0, s = i || "", w.readyState = e > 0 ? 4 : 0, r = e >= 200 && 300 > e || 304 === e, n && (y = z(d, w, n)), y = B(d, y, w, r), r ? (d.ifModified && (b = w.getResponseHeader("Last-Modified"), b && (re.lastModified[o] = b), b = w.getResponseHeader("etag"), b && (re.etag[o] = b)), 204 === e || "HEAD" === d.type ? S = "nocontent" : 304 === e ? S = "notmodified" : (S = y.state, u = y.data, v = y.error, r = !v)) : (v = S, (e || !S) && (S = "error", 0 > e && (e = 0))), w.status = e, w.statusText = (t || S) + "", r ? h.resolveWith(p, [u, S, w]) : h.rejectWith(p, [w, S, v]), w.statusCode(m), m = void 0, l && f.trigger(r ? "ajaxSuccess" : "ajaxError", [w, d, r ? u : v]), g.fireWith(p, [w, S]), l && (f.trigger("ajaxComplete", [w, d]), --re.active || re.event.trigger("ajaxStop")))
            }
            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var i, r, o, s, a, l, c, u, d = re.ajaxSetup({}, t), p = d.context || d, f = d.context && (p.nodeType || p.jquery) ? re(p) : re.event, h = re.Deferred(), g = re.Callbacks("once memory"), m = d.statusCode || {}, v = {}, y = {}, x = 0, b = "canceled", w = {readyState: 0, getResponseHeader: function (e) {
                    var t;
                    if (2 === x) {
                        if (!u)
                            for (u = {}; t = qt.exec(s); )
                                u[t[1].toLowerCase()] = t[2];
                        t = u[e.toLowerCase()]
                    }
                    return null == t ? null : t
                }, getAllResponseHeaders: function () {
                    return 2 === x ? s : null
                }, setRequestHeader: function (e, t) {
                    var n = e.toLowerCase();
                    return x || (e = y[n] = y[n] || e, v[e] = t), this
                }, overrideMimeType: function (e) {
                    return x || (d.mimeType = e), this
                }, statusCode: function (e) {
                    var t;
                    if (e)
                        if (2 > x)
                            for (t in e)
                                m[t] = [m[t], e[t]];
                        else
                            w.always(e[w.status]);
                    return this
                }, abort: function (e) {
                    var t = e || b;
                    return c && c.abort(t), n(0, t), this
                }};
            if (h.promise(w).complete = g.add, w.success = w.done, w.error = w.fail, d.url = ((e || d.url || Lt) + "").replace(Mt, "").replace(Pt, jt[1] + "//"), d.type = t.method || t.type || d.method || d.type, d.dataTypes = re.trim(d.dataType || "*").toLowerCase().match(xe) || [""], null == d.crossDomain && (i = It.exec(d.url.toLowerCase()), d.crossDomain = !(!i || i[1] === jt[1] && i[2] === jt[2] && (i[3] || ("http:" === i[1] ? "80" : "443")) === (jt[3] || ("http:" === jt[1] ? "80" : "443")))), d.data && d.processData && "string" != typeof d.data && (d.data = re.param(d.data, d.traditional)), P(zt, d, t, w), 2 === x)
                return w;
            l = re.event && d.global, l && 0 === re.active++ && re.event.trigger("ajaxStart"), d.type = d.type.toUpperCase(), d.hasContent = !Ot.test(d.type), o = d.url, d.hasContent || (d.data && (o = d.url += (_t.test(o) ? "&" : "?") + d.data, delete d.data), d.cache === !1 && (d.url = Ht.test(o) ? o.replace(Ht, "$1_=" + Wt++) : o + (_t.test(o) ? "&" : "?") + "_=" + Wt++)), d.ifModified && (re.lastModified[o] && w.setRequestHeader("If-Modified-Since", re.lastModified[o]), re.etag[o] && w.setRequestHeader("If-None-Match", re.etag[o])), (d.data && d.hasContent && d.contentType !== !1 || t.contentType) && w.setRequestHeader("Content-Type", d.contentType), w.setRequestHeader("Accept", d.dataTypes[0] && d.accepts[d.dataTypes[0]] ? d.accepts[d.dataTypes[0]] + ("*" !== d.dataTypes[0] ? ", " + Rt + "; q=0.01" : "") : d.accepts["*"]);
            for (r in d.headers)
                w.setRequestHeader(r, d.headers[r]);
            if (d.beforeSend && (d.beforeSend.call(p, w, d) === !1 || 2 === x))
                return w.abort();
            b = "abort";
            for (r in{success:1, error:1, complete:1})
                w[r](d[r]);
            if (c = P(Bt, d, t, w)) {
                w.readyState = 1, l && f.trigger("ajaxSend", [w, d]), d.async && d.timeout > 0 && (a = setTimeout(function () {
                    w.abort("timeout")
                }, d.timeout));
                try {
                    x = 1, c.send(v, n)
                } catch (S) {
                    if (!(2 > x))
                        throw S;
                    n(-1, S)
                }
            } else
                n(-1, "No Transport");
            return w
        }, getJSON: function (e, t, n) {
            return re.get(e, t, n, "json")
        }, getScript: function (e, t) {
            return re.get(e, void 0, t, "script")
        }}), re.each(["get", "post"], function (e, t) {
        re[t] = function (e, n, i, r) {
            return re.isFunction(n) && (r = r || i, i = n, n = void 0), re.ajax({url: e, type: t, dataType: r, data: n, success: i})
        }
    }), re._evalUrl = function (e) {
        return re.ajax({url: e, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
    }, re.fn.extend({wrapAll: function (e) {
            if (re.isFunction(e))
                return this.each(function (t) {
                    re(this).wrapAll(e.call(this, t))
                });
            if (this[0]) {
                var t = re(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType; )
                        e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        }, wrapInner: function (e) {
            return re.isFunction(e) ? this.each(function (t) {
                re(this).wrapInner(e.call(this, t))
            }) : this.each(function () {
                var t = re(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        }, wrap: function (e) {
            var t = re.isFunction(e);
            return this.each(function (n) {
                re(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function () {
            return this.parent().each(function () {
                re.nodeName(this, "body") || re(this).replaceWith(this.childNodes)
            }).end()
        }}), re.expr.filters.hidden = function (e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !ne.reliableHiddenOffsets() && "none" === (e.style && e.style.display || re.css(e, "display"))
    }, re.expr.filters.visible = function (e) {
        return!re.expr.filters.hidden(e)
    };
    var Vt = /%20/g, Qt = /\[\]$/, Ut = /\r?\n/g, Yt = /^(?:submit|button|image|reset|file)$/i, Jt = /^(?:input|select|textarea|keygen)/i;
    re.param = function (e, t) {
        var n, i = [], r = function (e, t) {
            t = re.isFunction(t) ? t() : null == t ? "" : t, i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
        };
        if (void 0 === t && (t = re.ajaxSettings && re.ajaxSettings.traditional), re.isArray(e) || e.jquery && !re.isPlainObject(e))
            re.each(e, function () {
                r(this.name, this.value)
            });
        else
            for (n in e)
                R(n, e[n], t, r);
        return i.join("&").replace(Vt, "+")
    }, re.fn.extend({serialize: function () {
            return re.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                var e = re.prop(this, "elements");
                return e ? re.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !re(this).is(":disabled") && Jt.test(this.nodeName) && !Yt.test(e) && (this.checked || !We.test(e))
            }).map(function (e, t) {
                var n = re(this).val();
                return null == n ? null : re.isArray(n) ? re.map(n, function (e) {
                    return{name: t.name, value: e.replace(Ut, "\r\n")}
                }) : {name: t.name, value: n.replace(Ut, "\r\n")}
            }).get()
        }}), re.ajaxSettings.xhr = void 0 !== e.ActiveXObject ? function () {
        return!this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && X() || V()
    } : X;
    var Gt = 0, Kt = {}, Zt = re.ajaxSettings.xhr();
    e.attachEvent && e.attachEvent("onunload", function () {
        for (var e in Kt)
            Kt[e](void 0, !0)
    }), ne.cors = !!Zt && "withCredentials"in Zt, Zt = ne.ajax = !!Zt, Zt && re.ajaxTransport(function (e) {
        if (!e.crossDomain || ne.cors) {
            var t;
            return{send: function (n, i) {
                    var r, o = e.xhr(), s = ++Gt;
                    if (o.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)
                        for (r in e.xhrFields)
                            o[r] = e.xhrFields[r];
                    e.mimeType && o.overrideMimeType && o.overrideMimeType(e.mimeType), e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                    for (r in n)
                        void 0 !== n[r] && o.setRequestHeader(r, n[r] + "");
                    o.send(e.hasContent && e.data || null), t = function (n, r) {
                        var a, l, c;
                        if (t && (r || 4 === o.readyState))
                            if (delete Kt[s], t = void 0, o.onreadystatechange = re.noop, r)
                                4 !== o.readyState && o.abort();
                            else {
                                c = {}, a = o.status, "string" == typeof o.responseText && (c.text = o.responseText);
                                try {
                                    l = o.statusText
                                } catch (u) {
                                    l = ""
                                }
                                a || !e.isLocal || e.crossDomain ? 1223 === a && (a = 204) : a = c.text ? 200 : 404
                            }
                        c && i(a, l, c, o.getAllResponseHeaders())
                    }, e.async ? 4 === o.readyState ? setTimeout(t) : o.onreadystatechange = Kt[s] = t : t()
                }, abort: function () {
                    t && t(void 0, !0)
                }}
        }
    }), re.ajaxSetup({accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"}, contents: {script: /(?:java|ecma)script/}, converters: {"text script": function (e) {
                return re.globalEval(e), e
            }}}), re.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), re.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, n = he.head || re("head")[0] || he.documentElement;
            return{send: function (i, r) {
                    t = he.createElement("script"), t.async = !0, e.scriptCharset && (t.charset = e.scriptCharset), t.src = e.url, t.onload = t.onreadystatechange = function (e, n) {
                        (n || !t.readyState || /loaded|complete/.test(t.readyState)) && (t.onload = t.onreadystatechange = null, t.parentNode && t.parentNode.removeChild(t), t = null, n || r(200, "success"))
                    }, n.insertBefore(t, n.firstChild)
                }, abort: function () {
                    t && t.onload(void 0, !0)
                }}
        }
    });
    var en = [], tn = /(=)\?(?=&|$)|\?\?/;
    re.ajaxSetup({jsonp: "callback", jsonpCallback: function () {
            var e = en.pop() || re.expando + "_" + Wt++;
            return this[e] = !0, e
        }}), re.ajaxPrefilter("json jsonp", function (t, n, i) {
        var r, o, s, a = t.jsonp !== !1 && (tn.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && tn.test(t.data) && "data");
        return a || "jsonp" === t.dataTypes[0] ? (r = t.jsonpCallback = re.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, a ? t[a] = t[a].replace(tn, "$1" + r) : t.jsonp !== !1 && (t.url += (_t.test(t.url) ? "&" : "?") + t.jsonp + "=" + r), t.converters["script json"] = function () {
            return s || re.error(r + " was not called"), s[0]
        }, t.dataTypes[0] = "json", o = e[r], e[r] = function () {
            s = arguments
        }, i.always(function () {
            e[r] = o, t[r] && (t.jsonpCallback = n.jsonpCallback, en.push(r)), s && re.isFunction(o) && o(s[0]), s = o = void 0
        }), "script") : void 0
    }), re.parseHTML = function (e, t, n) {
        if (!e || "string" != typeof e)
            return null;
        "boolean" == typeof t && (n = t, t = !1), t = t || he;
        var i = de.exec(e), r = !n && [];
        return i ? [t.createElement(i[1])] : (i = re.buildFragment([e], t, r), r && r.length && re(r).remove(), re.merge([], i.childNodes))
    };
    var nn = re.fn.load;
    re.fn.load = function (e, t, n) {
        if ("string" != typeof e && nn)
            return nn.apply(this, arguments);
        var i, r, o, s = this, a = e.indexOf(" ");
        return a >= 0 && (i = re.trim(e.slice(a, e.length)), e = e.slice(0, a)), re.isFunction(t) ? (n = t, t = void 0) : t && "object" == typeof t && (o = "POST"), s.length > 0 && re.ajax({url: e, type: o, dataType: "html", data: t}).done(function (e) {
            r = arguments, s.html(i ? re("<div>").append(re.parseHTML(e)).find(i) : e)
        }).complete(n && function (e, t) {
            s.each(n, r || [e.responseText, t, e])
        }), this
    }, re.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        re.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), re.expr.filters.animated = function (e) {
        return re.grep(re.timers, function (t) {
            return e === t.elem
        }).length
    };
    var rn = e.document.documentElement;
    re.offset = {setOffset: function (e, t, n) {
            var i, r, o, s, a, l, c, u = re.css(e, "position"), d = re(e), p = {};
            "static" === u && (e.style.position = "relative"), a = d.offset(), o = re.css(e, "top"), l = re.css(e, "left"), c = ("absolute" === u || "fixed" === u) && re.inArray("auto", [o, l]) > -1, c ? (i = d.position(), s = i.top, r = i.left) : (s = parseFloat(o) || 0, r = parseFloat(l) || 0), re.isFunction(t) && (t = t.call(e, n, a)), null != t.top && (p.top = t.top - a.top + s), null != t.left && (p.left = t.left - a.left + r), "using"in t ? t.using.call(e, p) : d.css(p)
        }}, re.fn.extend({offset: function (e) {
            if (arguments.length)
                return void 0 === e ? this : this.each(function (t) {
                    re.offset.setOffset(this, e, t)
                });
            var t, n, i = {top: 0, left: 0}, r = this[0], o = r && r.ownerDocument;
            if (o)
                return t = o.documentElement, re.contains(t, r) ? (typeof r.getBoundingClientRect !== Te && (i = r.getBoundingClientRect()), n = Q(o), {top: i.top + (n.pageYOffset || t.scrollTop) - (t.clientTop || 0), left: i.left + (n.pageXOffset || t.scrollLeft) - (t.clientLeft || 0)}) : i
        }, position: function () {
            if (this[0]) {
                var e, t, n = {top: 0, left: 0}, i = this[0];
                return"fixed" === re.css(i, "position") ? t = i.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), re.nodeName(e[0], "html") || (n = e.offset()), n.top += re.css(e[0], "borderTopWidth", !0), n.left += re.css(e[0], "borderLeftWidth", !0)), {top: t.top - n.top - re.css(i, "marginTop", !0), left: t.left - n.left - re.css(i, "marginLeft", !0)}
            }
        }, offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent || rn; e && !re.nodeName(e, "html") && "static" === re.css(e, "position"); )
                    e = e.offsetParent;
                return e || rn
            })
        }}), re.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (e, t) {
        var n = /Y/.test(t);
        re.fn[e] = function (i) {
            return Ae(this, function (e, i, r) {
                var o = Q(e);
                return void 0 === r ? o ? t in o ? o[t] : o.document.documentElement[i] : e[i] : void(o ? o.scrollTo(n ? re(o).scrollLeft() : r, n ? r : re(o).scrollTop()) : e[i] = r)
            }, e, i, arguments.length, null)
        }
    }), re.each(["top", "left"], function (e, t) {
        re.cssHooks[t] = k(ne.pixelPosition, function (e, n) {
            return n ? (n = tt(e, t), it.test(n) ? re(e).position()[t] + "px" : n) : void 0
        })
    }), re.each({Height: "height", Width: "width"}, function (e, t) {
        re.each({padding: "inner" + e, content: t, "": "outer" + e}, function (n, i) {
            re.fn[i] = function (i, r) {
                var o = arguments.length && (n || "boolean" != typeof i), s = n || (i === !0 || r === !0 ? "margin" : "border");
                return Ae(this, function (t, n, i) {
                    var r;
                    return re.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (r = t.documentElement, Math.max(t.body["scroll" + e], r["scroll" + e], t.body["offset" + e], r["offset" + e], r["client" + e])) : void 0 === i ? re.css(t, n, s) : re.style(t, n, i, s)
                }, t, o ? i : void 0, o, null)
            }
        })
    }), re.fn.size = function () {
        return this.length
    }, re.fn.andSelf = re.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
        return re
    });
    var on = e.jQuery, sn = e.$;
    return re.noConflict = function (t) {
        return e.$ === re && (e.$ = sn), t && e.jQuery === re && (e.jQuery = on), re
    }, typeof t === Te && (e.jQuery = e.$ = re), re
}), function (e) {
    var t = {}, n = {mode: "horizontal", slideSelector: "", infiniteLoop: !0, hideControlOnEnd: !1, speed: 500, easing: null, slideMargin: 0, startSlide: 0, randomStart: !1, captions: !1, ticker: !1, tickerHover: !1, adaptiveHeight: !1, adaptiveHeightSpeed: 500, video: !1, useCSS: !0, preloadImages: "visible", responsive: !0, slideZIndex: 50, wrapperClass: "bx-wrapper", touchEnabled: !0, swipeThreshold: 50, oneToOneTouch: !0, preventDefaultSwipeX: !0, preventDefaultSwipeY: !1, pager: !0, pagerType: "full", pagerShortSeparator: " / ", pagerSelector: null, buildPager: null, pagerCustom: null, controls: !0, nextText: "Next", prevText: "Prev", nextSelector: null, prevSelector: null, autoControls: !1, startText: "Start", stopText: "Stop", autoControlsCombine: !1, autoControlsSelector: null, auto: !1, pause: 4e3, autoStart: !0, autoDirection: "next", autoHover: !1, autoDelay: 0, autoSlideForOnePage: !1, minSlides: 1, maxSlides: 1, moveSlides: 0, slideWidth: 0, onSliderLoad: function () {
        }, onSlideBefore: function () {
        }, onSlideAfter: function () {
        }, onSlideNext: function () {
        }, onSlidePrev: function () {
        }, onSliderResize: function () {
        }};
    e.fn.bxSlider = function (r) {
        if (0 == this.length)
            return this;
        if (this.length > 1)
            return this.each(function () {
                e(this).bxSlider(r)
            }), this;
        var o = {}, s = this;
        t.el = this;
        var a = e(window).width(), l = e(window).height(), c = function () {
            o.settings = e.extend({}, n, r), o.settings.slideWidth = parseInt(o.settings.slideWidth), o.children = s.children(o.settings.slideSelector), o.children.length < o.settings.minSlides && (o.settings.minSlides = o.children.length), o.children.length < o.settings.maxSlides && (o.settings.maxSlides = o.children.length), o.settings.randomStart && (o.settings.startSlide = Math.floor(Math.random() * o.children.length)), o.active = {index: o.settings.startSlide}, o.carousel = o.settings.minSlides > 1 || o.settings.maxSlides > 1, o.carousel && (o.settings.preloadImages = "all"), o.minThreshold = o.settings.minSlides * o.settings.slideWidth + (o.settings.minSlides - 1) * o.settings.slideMargin, o.maxThreshold = o.settings.maxSlides * o.settings.slideWidth + (o.settings.maxSlides - 1) * o.settings.slideMargin, o.working = !1, o.controls = {}, o.interval = null, o.animProp = "vertical" == o.settings.mode ? "top" : "left", o.usingCSS = o.settings.useCSS && "fade" != o.settings.mode && function () {
                var e = document.createElement("div"), t = ["WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"];
                for (var n in t)
                    if (void 0 !== e.style[t[n]])
                        return o.cssPrefix = t[n].replace("Perspective", "").toLowerCase(), o.animProp = "-" + o.cssPrefix + "-transform", !0;
                return!1
            }(), "vertical" == o.settings.mode && (o.settings.maxSlides = o.settings.minSlides), s.data("origStyle", s.attr("style")), s.children(o.settings.slideSelector).each(function () {
                e(this).data("origStyle", e(this).attr("style"))
            }), u()
        }, u = function () {
            s.wrap('<div class="' + o.settings.wrapperClass + '"><div class="bx-viewport"></div></div>'), o.viewport = s.parent(), o.loader = e('<div class="bx-loading" />'), o.viewport.prepend(o.loader), s.css({width: "horizontal" == o.settings.mode ? 100 * o.children.length + 215 + "%" : "auto", position: "relative"}), o.usingCSS && o.settings.easing ? s.css("-" + o.cssPrefix + "-transition-timing-function", o.settings.easing) : o.settings.easing || (o.settings.easing = "swing");
            m();
            o.viewport.css({width: "100%", overflow: "hidden", position: "relative"}), o.viewport.parent().css({maxWidth: h()}), o.settings.pager || o.viewport.parent().css({margin: "0 auto 0px"}), o.children.css({"float": "horizontal" == o.settings.mode ? "left" : "none", listStyle: "none", position: "relative"}), o.children.css("width", g()), "horizontal" == o.settings.mode && o.settings.slideMargin > 0 && o.children.css("marginRight", o.settings.slideMargin), "vertical" == o.settings.mode && o.settings.slideMargin > 0 && o.children.css("marginBottom", o.settings.slideMargin), "fade" == o.settings.mode && (o.children.css({position: "absolute", zIndex: 0, display: "none"}), o.children.eq(o.settings.startSlide).css({zIndex: o.settings.slideZIndex, display: "block"})), o.controls.el = e('<div class="bx-controls" />'), o.settings.captions && C(), o.active.last = o.settings.startSlide == v() - 1, o.settings.video && s.fitVids();
            var t = o.children.eq(o.settings.startSlide);
            "all" == o.settings.preloadImages && (t = o.children), o.settings.ticker ? o.settings.pager = !1 : (o.settings.pager && S(), o.settings.controls && T(), o.settings.auto && o.settings.autoControls && E(), (o.settings.controls || o.settings.autoControls || o.settings.pager) && o.viewport.after(o.controls.el)), d(t, p)
        }, d = function (t, n) {
            var i = t.find("img, iframe").length;
            if (0 == i)
                return void n();
            var r = 0;
            t.find("img, iframe").each(function () {
                e(this).one("load", function () {
                    ++r == i && n()
                }).each(function () {
                    this.complete && e(this).load()
                })
            })
        }, p = function () {
            if (o.settings.infiniteLoop && "fade" != o.settings.mode && !o.settings.ticker) {
                var t = "vertical" == o.settings.mode ? o.settings.minSlides : o.settings.maxSlides, n = o.children.slice(0, t).clone().addClass("bx-clone"), i = o.children.slice(-t).clone().addClass("bx-clone");
                s.append(n).prepend(i)
            }
            o.loader.remove(), x(), "vertical" == o.settings.mode && (o.settings.adaptiveHeight = !0), o.viewport.height(f()), s.redrawSlider(), o.settings.onSliderLoad(o.active.index), o.initialized = !0, o.settings.responsive && e(window).bind("resize", z), o.settings.auto && o.settings.autoStart && (v() > 1 || o.settings.autoSlideForOnePage) && M(), o.settings.ticker && H(), o.settings.pager && _(o.settings.startSlide), o.settings.controls && L(), o.settings.touchEnabled && !o.settings.ticker && F()
        }, f = function () {
            var t = 0, n = e();
            if ("vertical" == o.settings.mode || o.settings.adaptiveHeight)
                if (o.carousel) {
                    var r = 1 == o.settings.moveSlides ? o.active.index : o.active.index * y();
                    for (n = o.children.eq(r), i = 1; i <= o.settings.maxSlides - 1; i++)
                        n = r + i >= o.children.length ? n.add(o.children.eq(i - 1)) : n.add(o.children.eq(r + i))
                } else
                    n = o.children.eq(o.active.index);
            else
                n = o.children;
            return"vertical" == o.settings.mode ? (n.each(function (n) {
                t += e(this).outerHeight()
            }), o.settings.slideMargin > 0 && (t += o.settings.slideMargin * (o.settings.minSlides - 1))) : t = Math.max.apply(Math, n.map(function () {
                return e(this).outerHeight(!1)
            }).get()), "border-box" == o.viewport.css("box-sizing") ? t += parseFloat(o.viewport.css("padding-top")) + parseFloat(o.viewport.css("padding-bottom")) + parseFloat(o.viewport.css("border-top-width")) + parseFloat(o.viewport.css("border-bottom-width")) : "padding-box" == o.viewport.css("box-sizing") && (t += parseFloat(o.viewport.css("padding-top")) + parseFloat(o.viewport.css("padding-bottom"))), t
        }, h = function () {
            var e = "100%";
            return o.settings.slideWidth > 0 && (e = "horizontal" == o.settings.mode ? o.settings.maxSlides * o.settings.slideWidth + (o.settings.maxSlides - 1) * o.settings.slideMargin : o.settings.slideWidth), e
        }, g = function () {
            var e = o.settings.slideWidth, t = o.viewport.width();
            return 0 == o.settings.slideWidth || o.settings.slideWidth > t && !o.carousel || "vertical" == o.settings.mode ? e = t : o.settings.maxSlides > 1 && "horizontal" == o.settings.mode && (t > o.maxThreshold || t < o.minThreshold && (e = (t - o.settings.slideMargin * (o.settings.minSlides - 1)) / o.settings.minSlides)), e
        }, m = function () {
            var e = 1;
            if ("horizontal" == o.settings.mode && o.settings.slideWidth > 0)
                if (o.viewport.width() < o.minThreshold)
                    e = o.settings.minSlides;
                else if (o.viewport.width() > o.maxThreshold)
                    e = o.settings.maxSlides;
                else {
                    var t = o.children.first().width() + o.settings.slideMargin;
                    e = Math.floor((o.viewport.width() + o.settings.slideMargin) / t)
                }
            else
                "vertical" == o.settings.mode && (e = o.settings.minSlides);
            return e
        }, v = function () {
            var e = 0;
            if (o.settings.moveSlides > 0)
                if (o.settings.infiniteLoop)
                    e = Math.ceil(o.children.length / y());
                else
                    for (var t = 0, n = 0; t < o.children.length; )
                        ++e, t = n + m(), n += o.settings.moveSlides <= m() ? o.settings.moveSlides : m();
            else
                e = Math.ceil(o.children.length / m());
            return e
        }, y = function () {
            return o.settings.moveSlides > 0 && o.settings.moveSlides <= m() ? o.settings.moveSlides : m()
        }, x = function () {
            if (o.children.length > o.settings.maxSlides && o.active.last && !o.settings.infiniteLoop) {
                if ("horizontal" == o.settings.mode) {
                    var e = o.children.last(), t = e.position();
                    b(-(t.left - (o.viewport.width() - e.outerWidth())), "reset", 0)
                } else if ("vertical" == o.settings.mode) {
                    var n = o.children.length - o.settings.minSlides, t = o.children.eq(n).position();
                    b(-t.top, "reset", 0)
                }
            } else {
                var t = o.children.eq(o.active.index * y()).position();
                o.active.index == v() - 1 && (o.active.last = !0), void 0 != t && ("horizontal" == o.settings.mode ? b(-t.left, "reset", 0) : "vertical" == o.settings.mode && b(-t.top, "reset", 0))
            }
        }, b = function (e, t, n, i) {
            if (o.usingCSS) {
                var r = "vertical" == o.settings.mode ? "translate3d(0, " + e + "px, 0)" : "translate3d(" + e + "px, 0, 0)";
                s.css("-" + o.cssPrefix + "-transition-duration", n / 1e3 + "s"), "slide" == t ? (s.css(o.animProp, r), s.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function () {
                    s.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd"), D()
                })) : "reset" == t ? s.css(o.animProp, r) : "ticker" == t && (s.css("-" + o.cssPrefix + "-transition-timing-function", "linear"), s.css(o.animProp, r), s.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function () {
                    s.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd"), b(i.resetValue, "reset", 0), q()
                }))
            } else {
                var a = {};
                a[o.animProp] = e, "slide" == t ? s.animate(a, n, o.settings.easing, function () {
                    D()
                }) : "reset" == t ? s.css(o.animProp, e) : "ticker" == t && s.animate(a, speed, "linear", function () {
                    b(i.resetValue, "reset", 0), q()
                })
            }
        }, w = function () {
            for (var t = "", n = v(), i = 0; n > i; i++) {
                var r = "";
                o.settings.buildPager && e.isFunction(o.settings.buildPager) ? (r = o.settings.buildPager(i), o.pagerEl.addClass("bx-custom-pager")) : (r = i + 1, o.pagerEl.addClass("bx-default-pager")), t += '<div class="bx-pager-item"><a href="" data-slide-index="' + i + '" class="bx-pager-link">' + r + "</a></div>"
            }
            o.pagerEl.html(t)
        }, S = function () {
            o.settings.pagerCustom ? o.pagerEl = e(o.settings.pagerCustom) : (o.pagerEl = e('<div class="bx-pager" />'), o.settings.pagerSelector ? e(o.settings.pagerSelector).html(o.pagerEl) : o.controls.el.addClass("bx-has-pager").append(o.pagerEl), w()), o.pagerEl.on("click", "a", W)
        }, T = function () {
            o.controls.next = e('<a class="bx-next" href="">' + o.settings.nextText + "</a>"), o.controls.prev = e('<a class="bx-prev" href="">' + o.settings.prevText + "</a>"), o.controls.next.bind("click", k), o.controls.prev.bind("click", N), o.settings.nextSelector && e(o.settings.nextSelector).append(o.controls.next), o.settings.prevSelector && e(o.settings.prevSelector).append(o.controls.prev), o.settings.nextSelector || o.settings.prevSelector || (o.controls.directionEl = e('<div class="bx-controls-direction" />'), o.controls.directionEl.append(o.controls.prev).append(o.controls.next), o.controls.el.addClass("bx-has-controls-direction").append(o.controls.directionEl))
        }, E = function () {
            o.controls.start = e('<div class="bx-controls-auto-item"><a class="bx-start" href="">' + o.settings.startText + "</a></div>"), o.controls.stop = e('<div class="bx-controls-auto-item"><a class="bx-stop" href="">' + o.settings.stopText + "</a></div>"), o.controls.autoEl = e('<div class="bx-controls-auto" />'), o.controls.autoEl.on("click", ".bx-start", $), o.controls.autoEl.on("click", ".bx-stop", A), o.settings.autoControlsCombine ? o.controls.autoEl.append(o.controls.start) : o.controls.autoEl.append(o.controls.start).append(o.controls.stop), o.settings.autoControlsSelector ? e(o.settings.autoControlsSelector).html(o.controls.autoEl) : o.controls.el.addClass("bx-has-controls-auto").append(o.controls.autoEl), j(o.settings.autoStart ? "stop" : "start")
        }, C = function () {
            o.children.each(function (t) {
                var n = e(this).find("img:first").attr("title");
                void 0 != n && ("" + n).length && e(this).append('<div class="bx-caption"><span>' + n + "</span></div>")
            })
        }, k = function (e) {
            o.settings.auto && s.stopAuto(), s.goToNextSlide(), e.preventDefault()
        }, N = function (e) {
            o.settings.auto && s.stopAuto(), s.goToPrevSlide(), e.preventDefault()
        }, $ = function (e) {
            s.startAuto(), e.preventDefault()
        }, A = function (e) {
            s.stopAuto(), e.preventDefault()
        }, W = function (t) {
            o.settings.auto && s.stopAuto();
            var n = e(t.currentTarget);
            if (void 0 !== n.attr("data-slide-index")) {
                var i = parseInt(n.attr("data-slide-index"));
                i != o.active.index && s.goToSlide(i), t.preventDefault()
            }
        }, _ = function (t) {
            var n = o.children.length;
            return"short" == o.settings.pagerType ? (o.settings.maxSlides > 1 && (n = Math.ceil(o.children.length / o.settings.maxSlides)), void o.pagerEl.html(t + 1 + o.settings.pagerShortSeparator + n)) : (o.pagerEl.find("a").removeClass("active"), void o.pagerEl.each(function (n, i) {
                e(i).find("a").eq(t).addClass("active")
            }))
        }, D = function () {
            if (o.settings.infiniteLoop) {
                var e = "";
                0 == o.active.index ? e = o.children.eq(0).position() : o.active.index == v() - 1 && o.carousel ? e = o.children.eq((v() - 1) * y()).position() : o.active.index == o.children.length - 1 && (e = o.children.eq(o.children.length - 1).position()), e && ("horizontal" == o.settings.mode ? b(-e.left, "reset", 0) : "vertical" == o.settings.mode && b(-e.top, "reset", 0))
            }
            o.working = !1, o.settings.onSlideAfter(o.children.eq(o.active.index), o.oldIndex, o.active.index)
        }, j = function (e) {
            o.settings.autoControlsCombine ? o.controls.autoEl.html(o.controls[e]) : (o.controls.autoEl.find("a").removeClass("active"), o.controls.autoEl.find("a:not(.bx-" + e + ")").addClass("active"))
        }, L = function () {
            1 == v() ? (o.controls.prev.addClass("disabled"), o.controls.next.addClass("disabled")) : !o.settings.infiniteLoop && o.settings.hideControlOnEnd && (0 == o.active.index ? (o.controls.prev.addClass("disabled"), o.controls.next.removeClass("disabled")) : o.active.index == v() - 1 ? (o.controls.next.addClass("disabled"), o.controls.prev.removeClass("disabled")) : (o.controls.prev.removeClass("disabled"), o.controls.next.removeClass("disabled")))
        }, M = function () {
            if (o.settings.autoDelay > 0) {
                setTimeout(s.startAuto, o.settings.autoDelay)
            } else
                s.startAuto();
            o.settings.autoHover && s.hover(function () {
                o.interval && (s.stopAuto(!0), o.autoPaused = !0)
            }, function () {
                o.autoPaused && (s.startAuto(!0), o.autoPaused = null)
            })
        }, H = function () {
            var t = 0;
            if ("next" == o.settings.autoDirection)
                s.append(o.children.clone().addClass("bx-clone"));
            else {
                s.prepend(o.children.clone().addClass("bx-clone"));
                var n = o.children.first().position();
                t = "horizontal" == o.settings.mode ? -n.left : -n.top
            }
            b(t, "reset", 0), o.settings.pager = !1, o.settings.controls = !1, o.settings.autoControls = !1, o.settings.tickerHover && !o.usingCSS && o.viewport.hover(function () {
                s.stop()
            }, function () {
                var t = 0;
                o.children.each(function (n) {
                    t += "horizontal" == o.settings.mode ? e(this).outerWidth(!0) : e(this).outerHeight(!0)
                });
                var n = o.settings.speed / t, i = "horizontal" == o.settings.mode ? "left" : "top", r = n * (t - Math.abs(parseInt(s.css(i))));
                q(r)
            }), q()
        }, q = function (e) {
            speed = e ? e : o.settings.speed;
            var t = {left: 0, top: 0}, n = {left: 0, top: 0};
            "next" == o.settings.autoDirection ? t = s.find(".bx-clone").first().position() : n = o.children.first().position();
            var i = "horizontal" == o.settings.mode ? -t.left : -t.top, r = "horizontal" == o.settings.mode ? -n.left : -n.top, a = {resetValue: r};
            b(i, "ticker", speed, a)
        }, F = function () {
            o.touch = {start: {x: 0, y: 0}, end: {x: 0, y: 0}}, o.viewport.bind("touchstart", O)
        }, O = function (e) {
            if (o.working)
                e.preventDefault();
            else {
                o.touch.originalPos = s.position();
                var t = e.originalEvent;
                o.touch.start.x = t.changedTouches[0].pageX, o.touch.start.y = t.changedTouches[0].pageY, o.viewport.bind("touchmove", P), o.viewport.bind("touchend", I)
            }
        }, P = function (e) {
            var t = e.originalEvent, n = Math.abs(t.changedTouches[0].pageX - o.touch.start.x), i = Math.abs(t.changedTouches[0].pageY - o.touch.start.y);
            if (3 * n > i && o.settings.preventDefaultSwipeX ? e.preventDefault() : 3 * i > n && o.settings.preventDefaultSwipeY && e.preventDefault(), "fade" != o.settings.mode && o.settings.oneToOneTouch) {
                var r = 0;
                if ("horizontal" == o.settings.mode) {
                    var s = t.changedTouches[0].pageX - o.touch.start.x;
                    r = o.touch.originalPos.left + s
                } else {
                    var s = t.changedTouches[0].pageY - o.touch.start.y;
                    r = o.touch.originalPos.top + s
                }
                b(r, "reset", 0)
            }
        }, I = function (e) {
            o.viewport.unbind("touchmove", P);
            var t = e.originalEvent, n = 0;
            if (o.touch.end.x = t.changedTouches[0].pageX, o.touch.end.y = t.changedTouches[0].pageY, "fade" == o.settings.mode) {
                var i = Math.abs(o.touch.start.x - o.touch.end.x);
                i >= o.settings.swipeThreshold && (o.touch.start.x > o.touch.end.x ? s.goToNextSlide() : s.goToPrevSlide(), s.stopAuto())
            } else {
                var i = 0;
                "horizontal" == o.settings.mode ? (i = o.touch.end.x - o.touch.start.x, n = o.touch.originalPos.left) : (i = o.touch.end.y - o.touch.start.y, n = o.touch.originalPos.top), !o.settings.infiniteLoop && (0 == o.active.index && i > 0 || o.active.last && 0 > i) ? b(n, "reset", 200) : Math.abs(i) >= o.settings.swipeThreshold ? (0 > i ? s.goToNextSlide() : s.goToPrevSlide(), s.stopAuto()) : b(n, "reset", 200)
            }
            o.viewport.unbind("touchend", I)
        }, z = function (t) {
            if (o.initialized) {
                var n = e(window).width(), i = e(window).height();
                (a != n || l != i) && (a = n, l = i, s.redrawSlider(), o.settings.onSliderResize.call(s, o.active.index))
            }
        };
        return s.goToSlide = function (t, n) {
            if (!o.working && o.active.index != t)
                if (o.working = !0, o.oldIndex = o.active.index, 0 > t ? o.active.index = v() - 1 : t >= v() ? o.active.index = 0 : o.active.index = t, o.settings.onSlideBefore(o.children.eq(o.active.index), o.oldIndex, o.active.index), "next" == n ? o.settings.onSlideNext(o.children.eq(o.active.index), o.oldIndex, o.active.index) : "prev" == n && o.settings.onSlidePrev(o.children.eq(o.active.index), o.oldIndex, o.active.index), o.active.last = o.active.index >= v() - 1, o.settings.pager && _(o.active.index), o.settings.controls && L(), "fade" == o.settings.mode)
                    o.settings.adaptiveHeight && o.viewport.height() != f() && o.viewport.animate({height: f()}, o.settings.adaptiveHeightSpeed), o.children.filter(":visible").fadeOut(o.settings.speed).css({zIndex: 0}), o.children.eq(o.active.index).css("zIndex", o.settings.slideZIndex + 1).fadeIn(o.settings.speed, function () {
                        e(this).css("zIndex", o.settings.slideZIndex), D()
                    });
                else {
                    o.settings.adaptiveHeight && o.viewport.height() != f() && o.viewport.animate({height: f()}, o.settings.adaptiveHeightSpeed);
                    var i = 0, r = {left: 0, top: 0};
                    if (!o.settings.infiniteLoop && o.carousel && o.active.last)
                        if ("horizontal" == o.settings.mode) {
                            var a = o.children.eq(o.children.length - 1);
                            r = a.position(), i = o.viewport.width() - a.outerWidth()
                        } else {
                            var l = o.children.length - o.settings.minSlides;
                            r = o.children.eq(l).position()
                        }
                    else if (o.carousel && o.active.last && "prev" == n) {
                        var c = 1 == o.settings.moveSlides ? o.settings.maxSlides - y() : (v() - 1) * y() - (o.children.length - o.settings.maxSlides), a = s.children(".bx-clone").eq(c);
                        r = a.position()
                    } else if ("next" == n && 0 == o.active.index)
                        r = s.find("> .bx-clone").eq(o.settings.maxSlides).position(), o.active.last = !1;
                    else if (t >= 0) {
                        var u = t * y();
                        r = o.children.eq(u).position()
                    }
                    if ("undefined" != typeof r) {
                        var d = "horizontal" == o.settings.mode ? -(r.left - i) : -r.top;
                        b(d, "slide", o.settings.speed)
                    }
                }
        }, s.goToNextSlide = function () {
            if (o.settings.infiniteLoop || !o.active.last) {
                var e = parseInt(o.active.index) + 1;
                s.goToSlide(e, "next")
            }
        }, s.goToPrevSlide = function () {
            if (o.settings.infiniteLoop || 0 != o.active.index) {
                var e = parseInt(o.active.index) - 1;
                s.goToSlide(e, "prev")
            }
        }, s.startAuto = function (e) {
            o.interval || (o.interval = setInterval(function () {
                "next" == o.settings.autoDirection ? s.goToNextSlide() : s.goToPrevSlide()
            }, o.settings.pause), o.settings.autoControls && 1 != e && j("stop"))
        }, s.stopAuto = function (e) {
            o.interval && (clearInterval(o.interval), o.interval = null, o.settings.autoControls && 1 != e && j("start"))
        }, s.getCurrentSlide = function () {
            return o.active.index
        }, s.getCurrentSlideElement = function () {
            return o.children.eq(o.active.index)
        }, s.getSlideCount = function () {
            return o.children.length
        }, s.redrawSlider = function () {
            o.children.add(s.find(".bx-clone")).width(g()), o.viewport.css("height", f()), o.settings.ticker || x(), o.active.last && (o.active.index = v() - 1), o.active.index >= v() && (o.active.last = !0), o.settings.pager && !o.settings.pagerCustom && (w(), _(o.active.index))
        }, s.destroySlider = function () {
            o.initialized && (o.initialized = !1, e(".bx-clone", this).remove(), o.children.each(function () {
                void 0 != e(this).data("origStyle") ? e(this).attr("style", e(this).data("origStyle")) : e(this).removeAttr("style")
            }), void 0 != e(this).data("origStyle") ? this.attr("style", e(this).data("origStyle")) : e(this).removeAttr("style"), e(this).unwrap().unwrap(), o.controls.el && o.controls.el.remove(), o.controls.next && o.controls.next.remove(), o.controls.prev && o.controls.prev.remove(), o.pagerEl && o.settings.controls && o.pagerEl.remove(), e(".bx-caption", this).remove(), o.controls.autoEl && o.controls.autoEl.remove(), clearInterval(o.interval), o.settings.responsive && e(window).unbind("resize", z))
        }, s.reloadSlider = function (e) {
            void 0 != e && (r = e), s.destroySlider(), c()
        }, c(), this
    }
}(jQuery), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Account = function () {
        function e() {
        }
        return e.defaultIndex = 1, e.dictionary = {}, e.$accountWrapper = $(".account__wrapper"), e.$accountAddress = $("#delivery_addresses").find(".section__article").first(), e.$accountPaymentMethod = $("#payment_methods").find(".section__article").first(), e.$slides = $(".section:not(#account)"), e.setup = function () {
            return EasyWay.Header.collapse(), this.storeDictionary(), this.createSlider(), this.bindEvents()
        }, e.createSlider = function () {
            return this.accountSlider = this.$accountWrapper.bxSlider({pageSelector: ".section", pager: !1, controls: !1, infiniteLoop: !1, touchEnabled: !1, adaptiveHeight: !0, slideMargin: 20, onSlideBefore: function (e, t, n) {
                    var i;
                    return i = $("#" + EasyWay.Account.dictionary[t] + ", #" + EasyWay.Account.dictionary[n]), EasyWay.Account.$slides.not(i).hide(), i.show()
                }})
        }, e.storeDictionary = function () {
            var e, t, n, i, r, o;
            for (i = $(".section"), r = [], t = e = 0, n = i.length; n > e; t = ++e)
                o = i[t], r.push(this.storeSection(o, t));
            return r
        }, e.storeSection = function (e, t) {
            return this.dictionary[t] = e.id, this.dictionary[e.id] = t
        }, e.goToStep = function (e) {
            var t;
            return t = this.dictionary[e] || 0, this.accountSlider.goToSlide(t)
        }, e.bindEvents = function () {
            return this.$accountWrapper.on("click", ".js-goto", function (e) {
                return function (t) {
                    var n;
                    return n = t.currentTarget.dataset.step, e.goToStep(n), !1
                }
            }(this)), this.$accountWrapper.on("click", ".js-save-address", function (e) {
                return function () {
                    return e.$accountAddress.before(e.$accountAddress.clone()), e.goToStep("delivery_addresses")
                }
            }(this)), this.$accountWrapper.on("click", ".js-save-payment-method", function (e) {
                return function () {
                    return e.$accountPaymentMethod.before(e.$accountPaymentMethod.clone()), e.goToStep("payment_methods")
                }
            }(this)), this.$accountWrapper.on("click", ".js-update-password", function (e) {
                return function () {
                    return window.location.hash = "#password-change", EasyWay.Notification.open()
                }
            }(this))
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Cart = function () {
        function e() {
        }
        return e.$cartTag = $("aside.cart"), e.$cartItemTag = e.$cartTag.find(".cart-item:first"), e.$cartTotalTag = e.$cartTag.find(".cart__total"), e.$cartItemListTag = e.$cartTag.find(".cart-item__list"), e.$cartEmpty = e.$cartTag.find(".cart-empty"), e.setup = function () {
            return this.emptyCart(), this.bindEvents()
        }, e.open = function () {
            //alert('cart open')
            // aaa();
            return this.$cartTag.addClass("open")
        }, e.close = function () {
            return this.$cartTag.removeClass("open")
        }, e.bindEvents = function () {
            return this.$cartTag.on("click", ".cart__title", function (e) {
                return function () {
                    return e.close()
                }
            }(this)), this.$cartTag.on("click", ".cart__place-order", function (e) {
                return function () {
                    return e.goToCheckout()
                }
            }(this))
        }, e.addItem = function () {
            var newItem = aaa($('.active_form'))
            return this.fillCart(), this.$cartItemListTag.append(newItem)
        }, e.emptyCart = function () {
            return this.$cartItemListTag.html(""), this.toggleCartContent(!0)
        }, e.fillCart = function () {
            return this.toggleCartContent(!1)
        }, e.toggleCartContent = function (e) {
            return this.$cartTotalTag.toggle(!e), this.$cartItemListTag.toggle(!e), this.$cartEmpty.toggle(e)
        }, e.goToCheckout = function () {
            return window.location.hash = "#login", EasyWay.Notification.open()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Checkout = function () {
        function e() {
        }
        return e.$footerTag = $(".footer"), e.activeForm = $("#checkout-form"), e.$deliveryOptions = $('input[name="delivery"]'), e.$methodOptions = $('input[name="method"]'), e.setup = function () {
            return EasyWay.Header.collapse(), this.overrideSliderOptions(), this.createStepper(), this.activeForm.toggleClass("open"), this.bindEvents()
        }, e.overrideSliderOptions = function () {
            return EasyWay.Stepper.sliderOptions.slideSelector = ".checkout__step", EasyWay.Stepper.sliderOptions.adaptiveHeight = !0
        }, e.createStepper = function () {
            return EasyWay.Stepper.create(this.activeForm)
        }, e.bindEvents = function () {
            return this.$footerTag.on("click", ".footer__stepper-next", function (e) {
                return function (t) {
                    var n;
                    return n = $(t.currentTarget).hasClass("js-submit"), n && e.submitForm() || EasyWay.Stepper.nextStep()
                }
            }(this)), this.$deliveryOptions.on("change", function () {
                return $(".bx-viewport").height("100%"), $("#pickup, #delivery").toggleClass("checkout__hidden")
            }), this.$methodOptions.on("change", function () {
                return $("#cash, #credit_card").toggleClass("checkout__hidden")
            })
        }, e.submitForm = function () {
            return window.location = "/thanks.html"
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Favorites = function () {
        function e() {
        }
        return e.dictionary = {}, e.$favoritesWrapper = $(".favorites__wrapper"), e.$footerAction = $(".footer__stepper-next"), e.setup = function () {
            return EasyWay.Header.collapse(), this.storeDictionary(), this.createSlider(), this.setFooterText(), this.bindEvents()
        }, e.createSlider = function () {
            return this.accountSlider = this.$favoritesWrapper.bxSlider({pageSelector: ".section", pager: !1, controls: !1, infiniteLoop: !1, touchEnabled: !1, adaptiveHeight: !0, slideMargin: 20, onSlideBefore: function (e, t, n) {
                    return EasyWay.Favorites.enableFooter(n)
                }})
        }, e.storeDictionary = function () {
            var e, t, n, i, r, o;
            for (i = $(".section"), r = [], t = e = 0, n = i.length; n > e; t = ++e)
                o = i[t], r.push(this.storeSection(o, t));
            return r
        }, e.storeSection = function (e, t) {
            return this.dictionary[e.id] = t
        }, e.goToStep = function (e) {
            var t;
            return t = this.dictionary[e] || 0, this.accountSlider.goToSlide(t)
        }, e.bindEvents = function () {
            return this.$favoritesWrapper.on("click", ".js-goto", function (e) {
                return function (t) {
                    var n;
                    return n = t.currentTarget.dataset.step, e.goToStep(n), !1
                }
            }(this)), this.$footerAction.on("click", function () {
                return window.location.href = "/checkout.html"
            })
        }, e.setFooterText = function () {
            return this.$footerAction.text("Order now")
        }, e.enableFooter = function (e) {
            return $("footer.footer").toggle(e)
        }, e.toggleEmpty = function () {
            return $("#favorites .section__article").toggle()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Footer = function () {
        function e() {
        }
        return e.$footerTag = $(".footer"), e.$footerActionTag = $(".footer__stepper-next"), e.isLastStep = function () {

            return this.$footerActionTag.hasClass("js-submit")
        }, e.submit = function (e) {
            return this.isLastStep() && e()
        }, e.updateActionText = function (e) {
            return this.$footerActionTag.text(e)
        }, e.show = function () {
            return this.$footerTag.show()
        }, e.hide = function () {
            return this.$footerTag.hide()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.GroupOrders = function () {
        function e() {
        }
        return e.activeForm = $("#group-orders-form"), e.$newOrder = $(".js-new-group-order"), e.$respondOrder = $(".js-respond-group-order"), e.setup = function () {
            return this.overrideSliderOptions(), this.bindEvents()
        }, e.overrideSliderOptions = function () {
            return EasyWay.Stepper.sliderOptions.slideSelector = ".js-step", EasyWay.Stepper.sliderOptions.adaptiveHeight = !0
        }, e.createStepper = function () {
            return EasyWay.Stepper.create(this.activeForm)
        }, e.bindEvents = function () {
            return this.$newOrder.on("click", function (e) {
                return function () {
                    return EasyWay.Footer.show(), e.toggleNewOrder(), e.createStepper()
                }
            }(this)), this.$respondOrder.on("click", function (e) {
                return function () {
                    return window.location.href = "/#make-order"
                }
            }(this)), EasyWay.Footer.$footerActionTag.on("click", function (e) {
                return function (t) {
                    return EasyWay.Footer.submit(e.submitForm) || EasyWay.Stepper.nextStep()
                }
            }(this))
        }, e.submitForm = function () {
            return window.location = "/#order-sent"
        }, e.toggleNewOrder = function () {
            return $(".section").toggle()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Header = function () {
        function e() {
        }
        return e.$headerTag = $("header.header"), e.collapse = function () {
            return this.$headerTag.addClass("collapsed")
        }, e.expand = function () {
            return this.$headerTag.removeClass("collapsed")
        }, e.bindEvents = function () {
            return this.$headerTag.on("click", ".header__cart", function (e) {
                return function () {
                    return e.openCart(), !1
                }
            }(this)), this.$headerTag.on("click", ".header__menu-icon", function (e) {
                return function () {
                    return e.openMainMenu(), !1
                }
            }(this))
        }, e.openCart = function () {
            return EasyWay.Cart.open(), EasyWay.MainMenu.close()
        }, e.openMainMenu = function () {
            return EasyWay.MainMenu.open(), EasyWay.Cart.close()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.MainMenu = function () {
        function e() {
        }
        return e.$mainMenuTag = $(".main-menu"), e.setup = function () {
            return this.bindEvents()
        }, e.open = function () {
            return this.$mainMenuTag.addClass("open")
        }, e.close = function () {
            return this.$mainMenuTag.removeClass("open")
        }, e.bindEvents = function () {
            return this.$mainMenuTag.on("click", ".main-menu__title", function (e) {
                return function () {
                    return e.close()
                }
            }(this))
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Menu = function () {
        function e() {
        }
        return e.$menuTag = $(".main__container > .menu"), e.$menuItemTag = $(".menu-item"), e.$quantity = $(".menu-item__quantity"), e.$footerTag = $(".footer"), e.bindEvents = function () {
            return this.$menuTag.on("click", ".menu__header", function (e) {
                return function (t) {
                    return e.activeMenu = $(t.currentTarget).next(".menu__content"), e.collapseHeader(), e.closeAllForms(), e.closeAllMenues(), e.activeMenu.toggleClass("open")
                }
            }(this)), this.$menuItemTag.on("click", ".menu-item__header", function (e) {
                return function (t) {
                    return e.activeForm = $(t.currentTarget).next(".menu-item__form"), e.closeAllForms(), e.activeForm.toggleClass("open"), e.createStepper()
                }
            }(this)), this.$quantity.on("click", ".menu-item__quantity-update", function (e) {
                return function (t) {
                    var n;
                    return n = $(t.currentTarget).hasClass("js-minus"), e.activeQuantity = $(t.delegateTarget).find(".menu-item__quantity-input"), n && e.decreaseQuantity() || e.increaseQuantity()
                }
            }(this)), this.$footerTag.on("click", ".footer__stepper-next", function (e) {
                return function (t) {
                    var n;
                    return n = $(t.currentTarget).hasClass("js-submit"), n && e.submitForm() || e.nextStep()
                }
            }(this))
        }, e.closeAllMenues = function () {
            return this.$menuTag.find(".menu__content").not(this.activeMenu).removeClass("open")
        }, e.closeAllForms = function () {
            return this.destroyStepper(), this.$menuItemTag.find(".menu-item__form").not(this.activeForm).removeClass("open")
        }, e.collapseHeader = function () {
            return EasyWay.Header.collapse()
        }, e.decreaseQuantity = function () {
            return EasyWay.Quantity.decrease(this.activeQuantity)
        }, e.increaseQuantity = function () {
            return EasyWay.Quantity.increase(this.activeQuantity)
        }, e.createStepper = function () {
            return EasyWay.Stepper.create(this.activeForm)
        }, e.destroyStepper = function () {
            return EasyWay.Stepper.destroy()
        }, e.nextStep = function () {
            return EasyWay.Stepper.nextStep()
        }, e.submitForm = function () {
            return EasyWay.Stepper.submitForm()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Notification = function () {
        function e() {
        }
        return e.$overlay = $("<div>", {"class": "notification__overlay"}), e.open = function () {
            var e, t;
            return e = window.location.hash, t = $(e), t.length ? (this.close(), this.bindEvents(), this.addBackground(), $(t).show(), "#out-of-delivery" === e ? this.addMap() : void 0) : void 0
        }, e.bindEvents = function () {
            return $(".notification__box-action").one("click", function (e) {
                return function () {
                    return e.close()
                }
            }(this)), this.$overlay.on("click", function (e) {
                return function () {
                    return e.close()
                }
            }(this))
        }, e.close = function () {
            return this.removeBackground(), $(".notification").hide(), !1
        }, e.addBackground = function () {
            return $("body").prepend(this.$overlay), this.$overlay.show()
        }, e.removeBackground = function () {
            return this.$overlay.hide()
        }, e.addMap = function () {
            var e, t;
            return e = document.getElementById("map"), t = {center: new google.maps.LatLng(44.5403, -78.5463), zoom: 8, mapTypeId: google.maps.MapTypeId.ROADMAP}, new google.maps.Map(e, t)
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Quantity = function () {
        function e() {
        }
        return e.decrease = function (e) {
            return e.val(function (e, t) {
                return t - 1 && --t || t
            })
        }, e.increase = function (e) {
            return e.val(function (e, t) {
                return++t
            })
        }, e.reset = function (e) {
            return e.val(1)
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Sign = function () {
        function e() {
        }
        return e.setup = function () {
            return $(".js-sign").on("click", function (e) {
                return function (e) {
                    return window.location.hash = e.currentTarget.hash, EasyWay.Notification.open()
                }
            }(this)), $(".js-continue-checkout").on("click", function () {
                return window.location.href = "/checkout.html"
            })
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.Stepper = function () {
        function e() {
        }
        return e.sliderOptions = {slideSelector: ".js-slide", infiniteLoop: !1, controls: !1, pagerSelector: ".footer__stepper", oneToOneTouch: !1, slideMargin: 20, buildPager: function () {
                return""
            }, onSliderLoad: function () {
                var e, t;
                return e = EasyWay.Stepper.form.find(this.slideSelector).first(), t = e.next().attr("data-name"), EasyWay.Stepper.updateNextAction(t)
            }, onSlideBefore: function (e, t, n) {
                var i, r;
                return i = e.next(), r = i.length && i.attr("data-name") || "Add to bag", EasyWay.Stepper.updateNextAction(r, "js-submit", !i.length)
            }, onSlideNext: function (e, t) {
                return EasyWay.Stepper.updateNavigationItem(t, !0)
            }, onSlidePrev: function (e, t) {
                return EasyWay.Stepper.updateNavigationItem(t, !1)
            }}, e.create = function (e) {
            return this.form = e, this.updateNextAction("", "js-submit", !1), this.slider = this.form.bxSlider(this.sliderOptions)
        }, e.destroy = function () {
            return this.sliderExist() ? ($(".bx-pager").remove(), this.slider.destroySlider()) : void 0
        }, e.sliderExist = function () {
            return this.slider && this.slider.length
        }, e.nextStep = function () {
            var activeIndex = $('.footer__stepper').find('.active').attr('data-slide-index')

            var element = $('.bx-wrapper').find('fieldset')[activeIndex];
            var req = $(element).attr('data-req')
            var input_type = $(element).attr('data-type')

            console.log(req)
            if (req == '1') {
                var req_field;
                if (input_type == '1')
                    req_field = $('.bx-wrapper').find('fieldset').find('[name=attr' + activeIndex + ']').val();
                else if (input_type == '2')
                    req_field = $('.bx-wrapper').find('fieldset').find('[name=attr' + activeIndex + '\\[\\]]:checked').val()
                else if (input_type == '3')
                    req_field = $('.bx-wrapper').find('fieldset').find('[name=attr' + activeIndex + ']:checked').val()
                if (!req_field) {
                    $($('.bx-wrapper').find('fieldset')[activeIndex ]).find('div').css('color', 'red')
                    return false
                } else {
                    $($('.bx-wrapper').find('fieldset')[activeIndex ]).find('div').css('color', 'inherit')
                }
            }
            return this.sliderExist() ? this.slider.goToNextSlide() : void 0
        }, e.updateNextAction = function (e, t, n) {
            return null == t && (t = ""), null == n && (n = !1), $(".footer__stepper-next").text(e).toggleClass(t, n)
        }, e.updateNavigationItem = function (e, t) {
            return $(".bx-pager-item a[data-slide-index='" + e + "']").toggleClass("done", t)
        }, e.submitForm = function () {
            var activeIndex = $('.footer__stepper').find('.active').attr('data-slide-index')
            var element = $('.bx-wrapper').find('fieldset')[activeIndex];
            var req = $(element).attr('data-req')
            var input_type = $(element).attr('data-type')

            console.log(req)
            if (req == '1') {
                var req_field;
                if (input_type == '1')
                    req_field = $('.bx-wrapper').find('fieldset').find('[name=attr' + activeIndex + ']').val();
                else if (input_type == '2')
                    req_field = $('.bx-wrapper').find('fieldset').find('[name=attr' + activeIndex + '\\[\\]]:checked').val()
                else if (input_type == '3')
                    req_field = $('.bx-wrapper').find('fieldset').find('[name=attr' + activeIndex + ']:checked').val()
                if (!req_field) {
                    $($('.bx-wrapper').find('fieldset')[activeIndex ]).find('div').css('color', 'red')
                    return false
                } else {
                    $($('.bx-wrapper').find('fieldset')[activeIndex ]).find('div').css('color', 'inherit')
                }
            }
            var e;
            return $(".open, .active").removeClass("open active"), this.destroy(), e = $("<span>", {text: "$49.50"}), $(".footer__stepper").html(e), this.updateNextAction("Place order", "js-submit", !1), EasyWay.Cart.addItem(), EasyWay.Cart.open()
        }, e
    }()
}.call(this), function () {
    window.EasyWay || (window.EasyWay = {}), EasyWay.ThemeSelector = function () {
        function e() {
        }
        return e.$styleTag = $("head > #theme"), e.appendSelector = function (e) {
            var t, n, i, r;
            for (this.themes = e, this.$select = $("<select>", {id:"theme-select", style:"position: absolute; top: 5px; left: 5px;"}), i = Object.keys(this.themes), t = 0, n = i.length; n > t; t++)
                r = i[t], this.appendOption(r);
            return $("body").append(this.$select), this.bindEvents()
        }, e.appendOption = function (e) {
            var t;
            return t = $("<option>", {value: this.themes[e], text: e}), this.$select.append(t)
        }, e.bindEvents = function () {
            return this.$select.on("change", function () {
                return EasyWay.ThemeSelector.$styleTag.attr("href", "/stylesheets/" + this.value)
            })
        }, e
    }()
}.call(this), function () {
}.call(this), function () {
    $(function () {
        return EasyWay.ThemeSelector.appendSelector({"default": "application.css", redish: "themes/redish.css"}), EasyWay.Header.bindEvents(), EasyWay.Header.collapse(), EasyWay.Cart.setup(), EasyWay.MainMenu.setup(), EasyWay.Notification.open(), EasyWay.Sign.setup()
    })
}.call(this);