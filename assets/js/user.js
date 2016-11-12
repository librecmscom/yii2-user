/**
 * 获取未读通知数
 * @param callback
 */
function getUnreadNotifications(callback) {
    callback = callback || jQuery.noop;
    jQuery.getJSON("/user/notification/unread-notifications", function (result) {
        return callback(result.total);
    });
}

/**
 * 发起关注
 * @param {string} source_type
 * @param {int} source_id
 * @param callback
 */
function attention(source_type, source_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/user/attention/store", {sourceType: source_type, sourceId: source_id}, function (result) {
        return callback(result.status);
    });
}

/**
 * 发起收藏
 * @param {string} source_type
 * @param {int} source_id
 * @param callback
 */
function collection(source_type, source_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/user/collection/store", {sourceType: source_type, sourceId: source_id}, function (result) {
        return callback(result.status);
    });
}
