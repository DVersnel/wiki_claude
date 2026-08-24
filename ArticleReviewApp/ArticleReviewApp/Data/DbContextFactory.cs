using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;

namespace ArticleReviewApp.Data;

public static class DbContextFactory
{
    private static readonly string _connectionString = new ConfigurationBuilder()
        .SetBasePath(AppContext.BaseDirectory)
        .AddJsonFile("appsettings.json", optional: false)
        .Build()
        .GetConnectionString("AppDb")
        ?? throw new InvalidOperationException("Missing 'AppDb' connection string in appsettings.json.");

    public static AppDbContext CreateDbContext()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseSqlServer(_connectionString)
            .Options;
        return new AppDbContext(options);
    }
}
