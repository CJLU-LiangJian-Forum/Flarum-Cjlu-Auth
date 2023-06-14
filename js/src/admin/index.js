import app from 'flarum/admin/app';
//import SettingsPage from './components/SettingsPage';
import UserListPage from './components/UserList'

app.initializers.add('cjlu-auth', () => {
  //app.extensionData.for('cjlu-auth').registerPage(SettingsPage);
  app.extensionData
    .for('foskytech-cjlu-auth')
    .registerSetting({
      label: app.translator.trans('cjlu-auth.admin.settings.serverUrl'),
      setting: 'cjlu-auth.serverUrl',
      type: 'string',
      placeholder: 'Wisedu-Login-Api 地址'
    });
  UserListPage();
});
