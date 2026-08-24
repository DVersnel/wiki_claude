using System.Configuration;
using System.Data;
using System.Windows;
using ArticleReviewApp.Repositories;

namespace ArticleReviewApp;

/// <summary>
/// Interaction logic for App.xaml
/// </summary>
public partial class App : Application
{
    protected override async void OnStartup(StartupEventArgs e)
    {
        base.OnStartup(e);

        try
        {
            var repo = new ArticleRepo();
            var articles = await repo.GetAllAsync();
            MessageBox.Show($"Loaded {articles.Count} articles.");
        }
        catch (Exception ex)
        {
            MessageBox.Show(ex.ToString());
        }
    }
}

