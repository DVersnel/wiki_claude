using System.Linq.Expressions;
using ArticleReviewApp.Data;
using ArticleReviewApp.Models;
using ArticleReviewApp.Models.Dtos;
using Microsoft.EntityFrameworkCore;

namespace ArticleReviewApp.Repositories;

public class ArticleRepo
{
    // Row-level access check, translated to SQL as: WHERE (access_mask & @roles) <> 0
    // An article is visible when its access mask shares at least one role bit with the user.
    private static Expression<Func<Article, bool>> CanAccess(Role roles) =>
        a => (a.AccessMask & roles) != Role.None;

    public async Task<List<Article>> GetAllAsync(Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Where(CanAccess(roles))
            .Include(a => a.User)
            .OrderBy(a => a.Name)
            .ToListAsync();
    }

    public async Task<List<ArticleSummary>> GetAllSummariesAsync(Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Where(CanAccess(roles))
            .OrderBy(a => a.Name)
            .Select(a => new ArticleSummary
            {
                Id = a.Id,
                Name = a.Name,
                AuthorName = a.User.Name,
                LastEdit = a.LastEdit,
                Status = a.Status
            })
            .ToListAsync();
    }

    public async Task<Article?> GetByIdAsync(int id, Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Where(CanAccess(roles))
            .Include(a => a.Images)
            .Include(a => a.User)
            .FirstOrDefaultAsync(a => a.Id == id);
    }

    public async Task<List<Article>> SearchByNameAsync(string term, Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        return await db.Articles
            .Where(CanAccess(roles))
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

    public async Task DeleteAsync(int id, Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        var article = await db.Articles
            .Where(CanAccess(roles))
            .FirstOrDefaultAsync(a => a.Id == id);
        if (article is null)
        {
            return;
        }

        db.Articles.Remove(article);
        await db.SaveChangesAsync();
    }
}
