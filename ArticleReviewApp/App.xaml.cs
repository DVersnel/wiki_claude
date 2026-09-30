using System.Configuration;
using System.Data;
using System.Windows;
using ArticleReviewApp.Repositories;
using ArticleReviewApp.Data;
using ArticleReviewApp.Views;

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

    protected override async void OnStartup(StartupEventArgs e)
    {
        base.OnStartup(e);

        var login = new LoginWindow();

        bool? result = login.ShowDialog();


        try
        {
            var repo = new ArticleRepo();
            var articles = await repo.GetAllSummariesAsync();
            MessageBox.Show($"Loaded {articles.Count} articles.");
        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.ToString());
        }
    }
}

