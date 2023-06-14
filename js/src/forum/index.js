import {extend} from 'flarum/extend';
import addAlert from './addAlert';
import app from 'flarum/app';

export * from './components/';
import SettingsPage from 'flarum/components/SettingsPage';

/*import UnlinkModal from "./components/UnlinkModal";*/
import LinkModal from "./components/LinkModal";

import Button from 'flarum/components/Button';

app.initializers.add('foskytech/cjlu-auth', () => {
  addAlert();

  extend(SettingsPage.prototype, 'accountItems', (items) => {
    const {
      data: {
        attributes: {
          CjluAuth: {
            isAuth = false
          },
        },
      },
    } = app.session.user;

    const user = app.session.user;
    if (user.data.attributes.CjluAuth.isAuth) {
      items.add(`linkCjluAuth`,
        <Button className={`Button linkCjluAuthButton--danger`} icon="fas fa-id-badge"
                disabled="true">
          {app.translator.trans(`cjlu-auth.forum.buttons.has_bind`)}
        </Button>
      );
    } else {

      items.add(`linkCjluAuth`,
        <Button className={`Button linkCjluAuthButton--success`} icon="fas fa-id-badge"
                onclick={() => app.modal.show(LinkModal)}>
          {app.translator.trans(`cjlu-auth.forum.buttons.link`)}
        </Button>
      );
    }


  });
});
