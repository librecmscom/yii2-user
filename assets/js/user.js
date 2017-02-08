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
 * 获取未读短消息数
 * @param callback
 */
function getUnreadMessages(callback) {
    callback = callback || jQuery.noop;
    jQuery.getJSON("/user/message/unread-messages", function (result) {
        return callback(result.total);
    });
}

/**
 * 发起赞
 * @param {string} source_type
 * @param {int} source_id
 * @param callback
 */
function support(source_type, source_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/user/support/store", {sourceType: source_type, sourceId: source_id}, function (result) {
        return callback(result.status);
    });
}

/**
 * 发起赞
 * @param {string} source_type
 * @param {int} source_id
 * @param callback
 */
function checkSupport(source_type, source_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/user/support/check", {sourceType: source_type, sourceId: source_id}, function (result) {
        return callback(result.status);
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
 * 发起关注
 * @param {string} source_type
 * @param {int} source_id
 * @param callback
 */
function attentionTag(source_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/user/attention/tag", {sourceId: source_id}, function (result) {
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
