using System.Windows;

namespace ArticleReviewApp;

/// <summary>
/// Interaction logic for MainWindow.xaml
/// </summary>
public partial class MainWindow : Window
{
    public MainWindow(MainWindowViewModel vm)
    {
        DataContext = vm;
        InitializeComponent();

        Width = SystemParameters.WorkArea.Width * 0.8;
        Height = SystemParameters.WorkArea.Height * 0.8;
        WindowStartupLocation = WindowStartupLocation.Manual;

        Loaded += (_, _) => vm.LoadAllArticleSummariesCommand.Execute(null);
    }
}
