(function(){window.EasyWay||(window.EasyWay={}),EasyWay.Footer=function(){function e(){}return e.$footerTag=$(".footer"),e.$footerActionTag=$(".footer__stepper-next"),e.isLastStep=function(){return this.$footerActionTag.hasClass("js-submit")},e.submit=function(e){return this.isLastStep()&&e()},e.updateActionText=function(e){return this.$footerActionTag.text(e)},e.show=function(){return this.$footerTag.show()},e.hide=function(){return this.$footerTag.hide()},e}()}).call(this);