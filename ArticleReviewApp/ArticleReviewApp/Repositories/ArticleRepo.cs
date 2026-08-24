using ArticleReviewApp.Data;
using ArticleReviewApp.Models;
using Microsoft.EntityFrameworkCore;

namespace ArticleReviewApp.Repositories;

public class ArticleRepo
{
    public async Task<List<Article>> GetAllAsync()
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Include(a => a.User)
            .OrderBy(a => a.Name)
            .ToListAsync();
    }

    public async Task<Article?> GetByIdAsync(int id)
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Include(a => a.Images)
            .Include(a => a.User)
            .FirstOrDefaultAsync(a => a.Id == id);
    }

    public async Task<List<Article>> SearchByNameAsync(string term)
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Where(a => EF.Functions.Like(a.Name, $"%{term}%"))
            .OrderBy(a => a.Name)
            .ToListAsync();
    }

    public async Task AddAsync(Article article)
    {
        using var db = DbContextFactory.CreateDbContext();
        db.Articles.Add(article);
        await db.SaveChangesAsync();
    }

    public async Task UpdateAsync(Article article)
    {
        using var db = DbContextFactory.CreateDbContext();
        db.Articles.Update(article);
        await db.SaveChangesAsync();
    }

    public async Task DeleteAsync(int id)
    {
        using var db = DbContextFactory.CreateDbContext();
        var article = await db.Articles.FirstOrDefaultAsync(a => a.Id == id);
        if (article is null)
        {
            return;
        }

        db.Articles.Remove(article);
        await db.SaveChangesAsync();
    }
}
