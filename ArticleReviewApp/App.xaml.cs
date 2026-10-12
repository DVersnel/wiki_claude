using System.Windows;

namespace ArticleReviewApp;

public partial class App : Application
{
    public App()
    {
        DispatcherUnhandledException += (_, args) =>
        {
            MessageBox.Show(args.Exception.ToString(), "Unexpected error");
            args.Handled = true;
        };
    }

    protected override void OnStartup(StartupEventArgs e)
    {
        base.OnStartup(e);

        var loginVm = new LoginViewModel();
        var login = new LoginWindow(loginVm);
        if (login.ShowDialog() != true || loginVm.AuthenticatedUser is not { } user)
        {
            Shutdown();
            return;
        }

        var main = new MainWindow(new MainWindowViewModel(user));
        MainWindow = main;
        ShutdownMode = ShutdownMode.OnMainWindowClose;
        main.Show();
    }
}
