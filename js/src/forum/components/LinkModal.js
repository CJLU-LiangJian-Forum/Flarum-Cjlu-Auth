import Modal from 'flarum/components/Modal';
import Button from 'flarum/components/Button';

export default class LinkModal extends Modal {
  className() {
    return `CjluAuthLinkModal Modal--small`;
  }

  title() {
    return app.translator.trans(`cjlu-auth.forum.modals.link.title`);
  }

  content() {
    return (
      <div className="Modal-body">
        <div className="Form Form--centered">
          <div className="Form-group">
            <input class="FormControl bottom"
                   className="user"
                   type="text"
                   placeholder={app.translator.trans(`cjlu-auth.forum.modals.link.user`)}
                   oninput={e => this.user = e.target.value}
                   disabled={this.inputDisabled}
            >
            </input>

            <input class="FormControl bottom"
                   className="pass"
                   type="password"
                   placeholder={app.translator.trans(`cjlu-auth.forum.modals.link.pass`)}
                   oninput={e => this.pass = e.target.value}
                   disabled={this.inputDisabled}
            >
            </input>

            <Button className={`Button Button--primary Button--block LogInButton--CjluAuth`} loading={this.loading} disabled={this.loading}
                    onclick={() => this.submit(this.user, this.pass)} >
              {app.translator.trans(`cjlu-auth.forum.buttons.submit`)}
            </Button>
          </div>

          <a href="https://authserver.cjlu.edu.cn" target="_blank">统一认证</a>
        </div>
      </div>
    );
  }

  submit(username, password) {
    let t = typeof username;
    let c = typeof password;
    if (t != 'string' || c != 'string') return;

    this.loading = true;
    this.inputDisabled = true;

    app
      .request({
        url: app.forum.attribute('apiUrl') + '/auth/cjlu/bind',
        method: 'POST',
        body: {
          username: username,
          password: password
        },
        errorHandler: this.onerror.bind(this),
      }).catch((error) => {
      this.inputDisabled = false;
      app.alerts.show(
        Alert,
        {type: 'error'},
        error
      );
    }).then((result) => {
      this.loading = false;
      this.inputDisabled = true;

      if (!result.status) {
        app.alerts.dismiss(alert);
        switch (result.msg) {
          case "user_exist":
            this.inputDisabled = false;
            app.alerts.show({type: 'error'}, app.translator.trans(`cjlu-auth.forum.alerts.user_exist`));
            break;
          case "verify_failed":
            this.inputDisabled = false;
            app.alerts.show({type: 'error'}, app.translator.trans(`cjlu-auth.forum.alerts.verify_failed`));
            break;
          default:
            this.inputDisabled = false;
            app.alerts.show({type: 'error'}, result.msg);
            break;
        }
        return;
      }
      app.alerts.show({type: 'success'}, app.translator.trans(`cjlu-auth.forum.alerts.link_success`));
      m.redraw();
      window.location.reload();
    });

    /*const user = app.session.user;
    user
      .save({
        username: username,
        password: password
      })
      .catch((error) => {
        app.alerts.show(
          Alert,
          {type: 'error'},
          error
        );
      })
      .then(() => {
        this.hide();
        app.alerts.show({type: 'success'}, app.translator.trans(`cjlu-auth.forum.alerts.link_success`));
        m.redraw();
        window.location.reload();
      });*/
  }
}
