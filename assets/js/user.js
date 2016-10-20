/**
 * Created by xutongle on 2016/10/17.
 */
function getUnreadNotifications() {
    jQuery.getJSON("/user/notification/unread-notifications", function (result) {
        return result.total;
    });
}