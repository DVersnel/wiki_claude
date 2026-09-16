using System;
using System.Collections.Generic;
using ArticleReviewApp.Models;
using Microsoft.EntityFrameworkCore;

namespace ArticleReviewApp.Data;

public partial class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options)
        : base(options)
    {
    }

    public virtual DbSet<Article> Articles { get; set; }

    public virtual DbSet<FormField> FormFields { get; set; }

    public virtual DbSet<HamburgerItem> HamburgerItems { get; set; }

    public virtual DbSet<Image> Images { get; set; }

    public virtual DbSet<JoinArticlesTag> JoinArticlesTags { get; set; }

    public virtual DbSet<NavItem> NavItems { get; set; }

    public virtual DbSet<Page> Pages { get; set; }

    public virtual DbSet<PageFooterLogoPath> PageFooterLogoPaths { get; set; }

    public virtual DbSet<PageTitleDescription> PageTitleDescriptions { get; set; }

    public virtual DbSet<Rating> Ratings { get; set; }

    public virtual DbSet<Tag> Tags { get; set; }

    public virtual DbSet<User> Users { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<Article>(entity =>
        {
            entity.ToTable("articles", tb => tb.HasTrigger("TRG_articles_last_edit"));

            entity.HasIndex(e => e.UserId, "IX_articles_user_id");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Code)
                .IsUnicode(false)
                .HasColumnName("code");
            entity.Property(e => e.Description)
                .HasMaxLength(1023)
                .IsUnicode(false)
                .HasColumnName("description");
            entity.Property(e => e.LastEdit)
                .HasPrecision(6)
                .HasDefaultValueSql("(sysutcdatetime())")
                .HasColumnName("last_edit");
            entity.Property(e => e.Name)
                .HasMaxLength(60)
                .IsUnicode(false)
                .HasColumnName("name");
            entity.Property(e => e.Status)
                .HasMaxLength(10)
                .IsUnicode(false)
                .HasDefaultValue("Pending")
                .HasColumnName("status");
            entity.Property(e => e.Text)
                .IsUnicode(false)
                .HasColumnName("text");
            entity.Property(e => e.UserId).HasColumnName("user_id");

            entity.HasOne(d => d.User).WithMany(p => p.Articles)
                .HasForeignKey(d => d.UserId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_articles_users");
        });

        modelBuilder.Entity<FormField>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("_form_fields");

            entity.Property(e => e.Label)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("label");
            entity.Property(e => e.Name)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("name");
            entity.Property(e => e.PageId).HasColumnName("page_id");
            entity.Property(e => e.Placeholder)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("placeholder");
            entity.Property(e => e.SortOrder)
                .HasDefaultValue(0)
                .HasColumnName("sort_order");
            entity.Property(e => e.Type)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("type");
        });

        modelBuilder.Entity<HamburgerItem>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("_hamburger_items");

            entity.Property(e => e.AClass)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("a_class");
            entity.Property(e => e.LiClass)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("li_class");
            entity.Property(e => e.Name)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("name");
            entity.Property(e => e.Page)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("page");
        });

        modelBuilder.Entity<Image>(entity =>
        {
            entity.ToTable("images", tb => tb.HasTrigger("TRG_images_last_edit"));

            entity.HasIndex(e => e.ArticleId, "IX_images_article_id");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.ArticleId).HasColumnName("article_id");
            entity.Property(e => e.Description)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("description");
            entity.Property(e => e.DisplayOrder).HasColumnName("display_order");
            entity.Property(e => e.LastEdit)
                .HasPrecision(6)
                .HasDefaultValueSql("(sysutcdatetime())")
                .HasColumnName("last_edit");
            entity.Property(e => e.Path)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("path");

            entity.HasOne(d => d.Article).WithMany(p => p.Images)
                .HasForeignKey(d => d.ArticleId)
                .HasConstraintName("FK_images_articles");
        });

        modelBuilder.Entity<JoinArticlesTag>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("join_articles_tags");

            entity.HasIndex(e => e.TagId, "IX_join_articles_tags_tag_id");

            entity.HasIndex(e => new { e.ArticleId, e.TagId }, "UQ_join_articles_tags").IsUnique();

            entity.Property(e => e.ArticleId).HasColumnName("article_id");
            entity.Property(e => e.TagId).HasColumnName("tag_id");

            entity.HasOne(d => d.Article).WithMany()
                .HasForeignKey(d => d.ArticleId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_join_articles_tags_articles");

            entity.HasOne(d => d.Tag).WithMany()
                .HasForeignKey(d => d.TagId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_join_articles_tags_tags");
        });

        modelBuilder.Entity<NavItem>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("_nav_items");

            entity.Property(e => e.AClass)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("a_class");
            entity.Property(e => e.LiClass)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("li_class");
            entity.Property(e => e.LoggedIn).HasColumnName("logged_in");
            entity.Property(e => e.Name)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("name");
            entity.Property(e => e.Page)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("page");
        });

        modelBuilder.Entity<Page>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("_pages");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Page1)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("page");
        });

        modelBuilder.Entity<PageFooterLogoPath>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("_page_footer_logo_path");

            entity.Property(e => e.Footer).HasColumnName("footer");
            entity.Property(e => e.LogoPath)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("logo_path");
        });

        modelBuilder.Entity<PageTitleDescription>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("_page_title_description");

            entity.Property(e => e.Page)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("page");
            entity.Property(e => e.PageDescription)
                .HasMaxLength(1000)
                .IsUnicode(false)
                .HasColumnName("page_description");
            entity.Property(e => e.PageTitle)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("page_title");
        });

        modelBuilder.Entity<Rating>(entity =>
        {
            entity
                .HasNoKey()
                .ToTable("ratings", tb => tb.HasTrigger("TRG_ratings_timestamp"));

            entity.HasIndex(e => e.ArticleId, "IX_ratings_article_id");

            entity.HasIndex(e => new { e.UserId, e.ArticleId }, "UQ_ratings").IsUnique();

            entity.Property(e => e.ArticleId).HasColumnName("article_id");
            entity.Property(e => e.Rating1).HasColumnName("rating");
            entity.Property(e => e.Timestamp)
                .HasPrecision(0)
                .HasDefaultValueSql("(sysutcdatetime())")
                .HasColumnName("timestamp");
            entity.Property(e => e.UserId).HasColumnName("user_id");

            entity.HasOne(d => d.Article).WithMany()
                .HasForeignKey(d => d.ArticleId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_ratings_articles");

            entity.HasOne(d => d.User).WithMany()
                .HasForeignKey(d => d.UserId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_ratings_users");
        });

        modelBuilder.Entity<Tag>(entity =>
        {
            entity.ToTable("tags");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Tag1)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("tag");
        });

        modelBuilder.Entity<User>(entity =>
        {
            entity.ToTable("users");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Email)
                .HasMaxLength(60)
                .IsUnicode(false)
                .HasColumnName("email");
            entity.Property(e => e.Name)
                .HasMaxLength(60)
                .IsUnicode(false)
                .HasColumnName("name");
            entity.Property(e => e.Password)
                .HasMaxLength(40)
                .IsUnicode(false)
                .HasColumnName("password");
        });

        OnModelCreatingPartial(modelBuilder);
    }

    partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
}
