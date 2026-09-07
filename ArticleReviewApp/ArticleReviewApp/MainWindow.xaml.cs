using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using ArticleReviewApp;

namespace ArticleReviewApp;

/// <summary>
/// Interaction logic for MainWindow.xaml
/// </summary>
public partial class MainWindow : Window
{
    public MainWindow()
    {
        var vm = new MainWindowViewModel();
        DataContext = vm;
        InitializeComponent();
        Loaded += async (_, _) =>
        {
            try
            {
                await vm.LoadArticle(1);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString(), "Failed to load article");
            }
        };
    }
}