this["Handlebars"] = this["Handlebars"] || {};

this["Handlebars"]["gallery_inline"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n      <li class=\"header-gallery-overlay\">\n        <img src=\"";
  if (helper = helpers.src) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.src); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\">\n      </li>\n    ";
  return buffer;
  }

  buffer += "<div id=\"inline-gallery-frame\" class=\"header-feature-bg header-gallery-frame frame\" >\n  <ul class=\"slidee\" data-type=\"inline\">\n    ";
  stack1 = helpers.each.call(depth0, (depth0 && depth0.gallery), {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n  </ul>\n</div>";
  return buffer;
  });