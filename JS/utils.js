function toJSON(arrayFromForm) {
  var json = {};
  arrayFromForm.forEach(function(element) {
    json["" + element.name] = element.value;
  });
  return json;
}
