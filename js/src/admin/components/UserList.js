import { extend } from 'flarum/common/extend';
import UserListPage from 'flarum/admin/components/UserListPage';

export default function () {
    extend(UserListPage.prototype, 'columns', (columns) => {
        columns.add('CjluAuthStatus', {
        name: 'Cjlu认证',
        content:(user) => {
          if(user.data.attributes.CjluAuth.isAuth){
            return <div style="color: #00c853">Yes</div>;
          } else {
            return <div style="color: #dd2c00">No</div>;
          }
        },
      });
    });
  }
