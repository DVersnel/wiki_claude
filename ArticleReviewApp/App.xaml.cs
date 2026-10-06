using System.Windows;

namespace ArticleReviewApp;

/// <summary>
/// Interaction logic for App.xaml
/// </summary>
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

        // ShutdownMode is OnExplicitShutdown (App.xaml) so closing the login
        // dialog doesn't end the app before the main window is shown.
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
