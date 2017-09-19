

/**
 * 发起关注
 * @param {int} model_id
 * @param callback
 */
function attentionTag(model_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/user/space/tag", {model_id: model_id}, function (result) {
        return callback(result.status);
    });
}